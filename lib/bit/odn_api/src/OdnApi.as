package  
{
	import flash.net.URLLoader;
	import flash.net.URLLoaderDataFormat;
	import flash.net.URLRequest;
	import flash.net.URLRequestMethod;
	import flash.net.URLVariables;
	import flash.events.Event;
	import flash.utils.ByteArray;
	import mx.controls.Alert;
	import com.adobe.crypto.MD5Stream;
	import mx.core.FlexGlobals;
  import flash.system.Security;
	import com.serialization.json.JSON;
	
  import com.api.forticom.ApiCallbackEvent;
	import com.api.forticom.ForticomAPI;
	import com.api.forticom.SignUtil;

  public class OdnApi
  {
    private var api_server : String;
    private var application_key : String;
    private var session_key : String;
    private var session_secret_key : String;
    private var js_port : JSPort;

    private var api_callbacks : Object = new Object;


    public function init() : void
    {
      Security.allowDomain('*');
      var params : Object = FlexGlobals.topLevelApplication.parameters;

      api_server = params.api_server || 'http://api.odnoklassniki.ru/';
      application_key = params.application_key;
      session_key = params.session_key;
      session_secret_key = params.session_secret_key;
			
      js_port = new JSPort(params.js_callback_object);
      js_port.addExternalCallback('apiCall', jsApiCall);
      
      try
      {
        SignUtil.applicationKey = application_key;
        SignUtil.sessionKey = session_key;
        SignUtil.secretSessionKey = session_secret_key;
        js_port.callToJs('swf_ready');
      } catch(e : *) {
        js_port.toDebug("Error: " + e.toString());
      }
    }

    private function jsApiCall(method : String, params : Object, call_id : String) : void
    {
      _apiCall(method, params)
      (function(data : *) : void {
        js_port.callToJs('apiCallback', {call_id: call_id, response: data});
      });
    }

    private function _apiCall(method : String, params : Object = null) : Function
    {
      params = params || {};
      params.method = method;
      params.application_key = application_key;
      params.format = 'JSON';
      params.call_id = Math.floor(Math.random() * 2000000000);
      params.session_key = session_key;

			var 
        sorted_array : Array = new Array(),
        key : String,
        signature : String = '';
			for(key in params)
				sorted_array.push(key);
			sorted_array.sort();
      for each(key in sorted_array)
				signature += key + '=' + params[key];
			
			var data : ByteArray = new ByteArray();
			data.writeMultiByte(signature + session_secret_key, 'utf-8');
			data.position = 0;
			var md5_stream : MD5Stream = new MD5Stream(); 
      data.readMultiByte(data.length, 'utf-8');
			md5_stream.update(data);

      params.sig = md5_stream.complete();

      return function(callback : Function) : void
      {
        _sendRequest(api_server +  'fb.do', params)
        (function(data : String) : void { 
          js_port.toDebug('response ' + method + ': ' + data);
          var response : Object;
          try {
            response = JSON.deserialize(data);
          } catch ( e : Error ) {
            response = null;
            js_port.toDebug('failed parse: ' + e.toString());
          };
          callback(response);
        });
      }
    }

    private function _sendRequest(url : String, params : Object) : Function
    {
			var loader : URLLoader = new URLLoader ();
			loader.dataFormat = URLLoaderDataFormat.TEXT;

			var request : URLRequest = new URLRequest(url);
			request.method = URLRequestMethod.POST;
      var varaibles : URLVariables = new URLVariables;
			for(var name : String in params) 
        varaibles[name] = params[name];
			request.data = varaibles;

      return function(callback : Function) : void 
      {
        loader.addEventListener(Event.COMPLETE, function(event : Event) : void {
          callback(loader.data);
        });
        loader.load(request);
      };
    }
  }
}
