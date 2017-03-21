<?php

class Unisender {


    function __construct(modX &$modx, array $config=array()) {
		$this->modx =& $modx;

		$namespace = $this->modx->getObject('modNamespace', 'unisender');
		
		$basePath = MODX_CORE_PATH.'components/unisender/';
		$assetsUrl = $this->modx->getOption('assets_url').'components/unisender/';
		
		$this->config = array_merge(array(
			'basePath' => $basePath,
			'corePath' => $basePath,
			'modelPath' => $basePath.'model/',
			'processorsPath' => $basePath.'processors/',
			//'chunksPath' => $basePath.'elements/chunks/',
			'jsUrl' => $assetsUrl.'js/',
			'cssUrl' => $assetsUrl.'css/',
			'assetsUrl' => $assetsUrl,
			'connectorUrl' => $assetsUrl.'connector.php',
		), $config);
	   
		$this->modx->addPackage('unisender', $this->config['modelPath']/*
 , 'modx_unisender_'
 */);
	}
	
	/**
     * Initializes the class into the proper context
     *
     * @access public
     * @param string $ctx
     */
    
     public function initialize($ctx='web') {
     	
     		switch ($ctx) {
     			case 'mgr':
     				$this->modx->lexicon->load('unisender:default');
     				//require $this->config['modelPath'] . 'unisender/request/unisenderControllerRequest.class.php';
                   // echo $this->config['modelPath'] . 'unisender/request/'; exit;
     				if(!$this->modx->loadClass('unisender.request.unisendercontrollerrequest', $this->config['modelPath'], true, true)) {
     					return 'Could not load controller request handler.';
     				}
     				
     				$this->request = new unisenderControllerRequest($this);
     				
     				return $this->request->handleRequest();
                 break;
     			
                 case 'connector':
     				if(!$this->modx->loadClass('unisender.request.unisenderconnectorrequest', $this->config['modelPath'], true, true)) {
                         die('Could not load connector request handler2.');
                     }
     				
     				$this->request = new unisenderConnectorRequest($this);
     				
     				return $this->request->handle();
                 break;
                 default: break;
             }
             return true;
         }
     
     public function getSubscribeTypeByUserId($id) {
        
        $email = 1;
        $phone = 2;
        $subscribeType = array('1'=>'Email', '2'=>'SMS');
         $c = $this->modx->newQuery('Subscribers');
         $c->where(array('user_id'=>$id));
         $result = $this->modx->getCollection('Subscribers',$c);
         $out = array();
         foreach($result as $res)
         {
            if(isset($subscribeType[$res->get(subscribe_type)]))
            {
                $out[] = $subscribeType[$res->get(subscribe_type)];    
            }
         }
         if(count($out) > 0)
            return implode(', ', $out);
         else
            return '';
     }

}
?>