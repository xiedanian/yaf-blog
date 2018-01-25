<?php

abstract class Ald_Action extends Yaf_Action_Abstract{


    const INPUT_TYPE_RAW = 'raw';
    const INPUT_TYPE_GET = 'get';
    const INPUT_TYPE_POST = 'post';
    const INPUT_TYPE_REQUEST = 'request';

    const OUTPUT_TYPE_JSON = 'json';
    const OUTPUT_TYPE_XML = 'xml';
    const OUTPUT_TYPE_HTML = 'html';  //html 渲染页面 需要有tpl属性支持
    const OUTPUT_TYPE_RAW = 'raw';    //直接echo输出

    const HTTP_METHOD_GET = 'get';
    const HTTP_METHOD_POST = 'post';
    const HTTP_METHOD_PUT = 'put';
    const HTTP_METHOD_DELETE = 'delete';

    private $__httpMethods = array(
        self::HTTP_METHOD_GET,
        self::HTTP_METHOD_POST,
        self::HTTP_METHOD_PUT,
        self::HTTP_METHOD_DELETE,
    );

    protected $_httpMethod = null;

    /**
     * 当前请求使用的接受参数的方式，默认为:http request
     * 如果想改变接受方式，直接在对应的子类中覆盖此属性即可
     */
    protected $_inputType = self::INPUT_TYPE_REQUEST;

    /**
     * 当前请求输出方式,默认为HTML页面，如果没有找到对应的资源则跳到错误页
     */
    protected $_outputType = self::OUTPUT_TYPE_JSON;
    
    /**
     * 模板路径，默认的显示方式
     */
    protected $_tpl = null;

    /**
     * 返回对象
     */
    protected $_result = null;
    
    /**
     * 异常模板
     * @var string
     */
    protected $_tplError = '';

    /**
     * 响应结果中是否输出参数
     * @var unknown
     */
    protected $_displayParams = FALSE;
    /**
     * 是否需要校验权限，默认都需要
     * @var unknown
     */
    protected $_checkAuth = TRUE;
    /**
     * 当前页面权限名称
     * @var unknown
     */
    protected $_authNodeName = '';
    /**
     * 默认执行入口名称
     * @var unknown
     */
    private $__defaultExecute = 'doExecute';

    /**
     * 校验规则，可以为不同的httpMethod定义不同的validator
     * 如$_validatorGet, $_validatorPost
     * 如果未定义，则使用默认的$_validator
     * @var unknown
     */
    protected $_validator = '';


    protected $_tplVars = array(
        //'_STATIC_' => '/static/' . APP_NAME,
        '_STATIC_' => '/static/visk',
        '_SITE_PATH_' => '',
    );

    protected function setTplVar($key, $val){
        $this->_tplVars[$key] = $val;
    }

    /**
     * 字段检查
     * @param $inputData
     */
    private function __validator(&$inputData){
        $validatorPropName = '_validator' . ucfirst($this->_httpMethod);
        $validator = property_exists($this, $validatorPropName) ? $this->$validatorPropName : $this->_validator;
        if(!empty($validator)){
            $objValidator = new Ald_Validate($inputData, $validator);
            $inputData = $objValidator -> validate();
        }
    }
    
    private function __init(){
        Yaf_Loader::getInstance()->setLibraryPath(APP_DIR . '/library');
        Yaf_Loader::getInstance()->registerLocalNameSpace(ucfirst(APP_NAME));
        if(!defined('YAF_ENVIRON')){
            define('YAF_ENVIRON',   ini_get('yaf.environ'));
        }
        define('NOW_TIME',      $_SERVER['REQUEST_TIME']);
        define('REQUEST_METHOD',$_SERVER['REQUEST_METHOD']);
        define('IS_GET',        REQUEST_METHOD =='GET' ? true : false);
        define('IS_POST',       REQUEST_METHOD =='POST' ? true : false);
        define('IS_PUT',        REQUEST_METHOD =='PUT' ? true : false);
        define('IS_DELETE',     REQUEST_METHOD =='DELETE' ? true : false);
        define('IS_APP',     (isset($_REQUEST['platform']) && (strtolower($_REQUEST['platform']) == 'android' || strtolower($_REQUEST['platform']) == 'ios')) ? true : false);
        $isRealAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        $isUserAjax = isset($_REQUEST['display']) && 'json' == $_REQUEST['display'];
        define('IS_AJAX',       ($isRealAjax || $isUserAjax) ? true : false);
    }

    /**
     * execute前需要执行的动作
     */
    protected function _before(&$inputData){
    }

    /**
     * execute后需要执行的动作
     */
    protected function _after(&$inputData){
    }

    public function execute(){
        $this -> _result = new Ald_Action_Result();
        $this->__init();
        $inputData = $this->_getInputData();
        try{
            Ald_Log::notice(sprintf('ACTION_INPUT[%s]', is_scalar($inputData) ? $inputData : json_encode($inputData)));
            $this->_detectHttpMethod();
            $this->_before($inputData);
            $this->_detectOutputType();
            $this->__validator($inputData);
            //子Action中如果有对应httpMehtod的实现，则优先调用对应对应的do${METHOD}的方法，否则调用doExecute方法
            $executeMethodName = 'do' . ucfirst($this->_httpMethod);
            if(!method_exists($this, $executeMethodName)){
                $executeMethodName = $this->__defaultExecute;
            }
            $ret = $this->$executeMethodName($inputData);
            $this->_debugHook();
            $this->_detectOutputType(); //action中可能会有输出行为的调整
            $this->_after($ret);
            $this -> _result -> setData($ret);
        }catch(Exception $e){
            $this->_detectOutputType();
            $_errno = $e->getCode();
            $_error = $e->getMessage();
            $_internalError = null;
            if(is_null($_errno)){
                $_errno = Ald_Const_Errno::ERROR;
            }
            if(is_null($_error)){
                $_error = Ald_Const_Error::error($_errno);
            }
            if(method_exists($e, 'getExceptionData')){
                $_data = $e->getExceptionData();
                $this -> _result -> setData($_data);
            }
            $this -> _result -> setErrno($_errno);
            $this -> _result -> setError($_error);
            if(method_exists($e, 'getInternalError')){
                $_internalError = $e->getInternalError();
            }
            $serializedExceptonData = is_scalar($this -> _result -> getData()) ? $this -> _result -> getData() : json_encode($this -> _result -> getData());
            $serializedInternalError = is_scalar($_internalError) ? $_internalError : json_encode($_internalError);
            $exceptionTraceStack = PHP_EOL . '## ' . $e->getFile() . '[' . $e->getLine() . ']';
            $exceptionTraceStack.= PHP_EOL . $e->getTraceAsString();
            Ald_Log::warning(sprintf('%s errno[%s] error[%s] data[%s] internal_error[%s] %s', 
                __METHOD__, $_errno, $_error, $serializedExceptonData, $serializedInternalError, $exceptionTraceStack));
        }
        $result = get_object_vars($this -> _result);
        if($this->_displayParams){
            $result['params'] = $this->_getInputData();
        }
        if($result['data'] === null){
            unset($result['data']);
        }
        Ald_Log::addNotice('ACTION_OUTPUT', json_encode($result));
        $this->__display($result);
    }
    
    /**
     * debug模式下手动指定模板调试
     */
    protected function _debugHook(){
        if(defined('IS_DEBUG') && 1 == IS_DEBUG){
            if(isset($_REQUEST['tpl']) && !empty($_REQUEST['tpl'])){
                $tpl = $_REQUEST['tpl'];
                $this->_tpl = $tpl;
            }
        }
    }

    /**
     * 获取HTTP请求参数
     * TOTO: XssFilter
     */
    protected function _getInputData(){
        $input = null;
        switch($this->_inputType){
            case self::INPUT_TYPE_RAW:
                $input = file_get_contents('php://input');
                break;
            case self::INPUT_TYPE_GET:
                $paramsGet = (array)$_GET;
                $paramsYafGet = $this->getRequest()->getParams();
                $input = array_merge($paramsYafGet, $paramsGet);
                break;
            case self::INPUT_TYPE_POST:
                $input = $this->getRequest()->getPost();
                break;
            case self::INPUT_TYPE_REQUEST:
                $paramsGet = $_GET;
                $paramsYafGet = $this->getRequest()->getParams();
                $paramsYafPost = $this->getRequest()->getPost();
                return array_merge($paramsYafGet, $paramsYafPost, $paramsGet);
                break;
            default:
        }
        return $input;
    }

    /**
     * 自动检测输入方式
     * 1.如果是POST/GET中指定display=json优先使用json输出
     * 2.非第一种情况下，如果检查到tpl属性值不为空默认为HTML模板输出
     */
    protected function _detectOutputType(){
        if(isset($_REQUEST['display']) && 'json' == $_REQUEST['display']){
            $this->_outputType = self::OUTPUT_TYPE_JSON;
            return true;
        }
        if(!empty($this->_tpl)){
            $this->_outputType = self::OUTPUT_TYPE_HTML;
            return true;
        }
        return true;
    }
    
    /**
     * 检查请求方法
     *
     */
    protected function _detectHttpMethod(){
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        switch($method){
            case self::HTTP_METHOD_GET:
                $this->_httpMethod = self::HTTP_METHOD_GET;
                break;
            case self::HTTP_METHOD_POST:
                $this->_httpMethod = self::HTTP_METHOD_POST;
                break;
            case self::HTTP_METHOD_PUT:
                $this->_httpMethod = self::HTTP_METHOD_PUT;
                break;
            case self::HTTP_METHOD_DELETE:
                $this->_httpMethod = self::HTTP_METHOD_DELETE;
                break;
            default:
                throw new Exception('unsupported http method');
        }
    }
    
    /**
     * 展示数据,目前只支持json和html
     * @param mixed $data 要展示的数据
     */
    private function __display($data){
        header('X-ALD-LOGID: ' . LOG_ID);
        switch($this->_outputType){
            case self::OUTPUT_TYPE_JSON:
                header("Content-Type: application/json;charset=utf-8");
                $json = json_encode($data);
                if(isset($_REQUEST['_callback'])){
                    $json = $_REQUEST['_callback'] . '(' . $json . ')';
                }
                echo $json;
                break;
            case self::OUTPUT_TYPE_HTML:
                try{
                    $objTpl = $this->getController()->getView();
                    foreach($this->_tplVars as $k=>$v){
                        $objTpl->assign($k, $v);
                    }
                    $objTpl->assign('tpl', $data);
                    if(0 != $data['errno'] && !empty($this->_tplError)){
                        $objTpl->display($this->_tplError);
                    }else{
                        $objTpl->display($this->_tpl);
                    }
                }catch(Exception $e){
                    echo $e->getMessage() . '<hr>';
                    Ald_Log::warning(sprintf('%s: exception: %s', __METHOD__, $e->getMessage()));
                }
                break;
            case self::OUTPUT_TYPE_RAW;
                echo $data['data'];
                break;
            default:
                echo "unsupported output type!";
        }
    }

    public function redirect($url){
        header('Localtion: ' . $url);
    }
}
