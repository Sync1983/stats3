package com.api.forticom//.ForticomAPI 
{
	import com.serialization.json.JSON;
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.StatusEvent;
	import flash.events.TimerEvent;
	import flash.external.ExternalInterface;
	import flash.net.LocalConnection;
	import flash.utils.Timer;

	/**
	 * @author Forticom Ltd.
	 * @date 18/02/2010 15:34
	 * @description Forticom API interface for applications;
	 */
	public class ForticomAPI extends EventDispatcher
	{
		public static const CONNECTED : String = "connection established";
		public static const SEND_ERROR : String = "send failed";
		public static const DEBUG : Boolean = true;
		
		private static var _instance : ForticomAPI;
		
		private var _timer : Timer = new Timer(200);
		private var _connectedSelf : Boolean = false;
		
		private var _wasRetry : Boolean = false;
		private var _connection : LocalConnection = new LocalConnection;
		private var _connectionName : String;
		private var _eventsPool : Array = [];
		private var _connected : Boolean = false;
		
		public function ForticomAPI(key : SingleTonKey) 
		{
			super();
			
			this._timer.addEventListener(TimerEvent.TIMER, this.tryToCreateConnection);
			
			this._connection.allowDomain("*");
			this._connection.addEventListener(StatusEvent.STATUS, this.handleOutStatus);
			this._connection.client = this;
			
		}
		
		private function handleOutStatus(event : StatusEvent) : void
		{
			if (event.level != StatusEvent.STATUS)
			{
        toDebug("SEND ERROR  " + event.level);
				this.dispatchEvent(new Event(ForticomAPI.SEND_ERROR));
			}
      else
        toDebug("SEND SUCCESS " + event.level);
		}
		
		public function tryToCreateConnection(event : TimerEvent = null) : void
		{
			try
			{
				if(!this._connectedSelf)
				{
					/*
					  If client connection, does not exists, try to create new one. 
					  If fails to create client connection, we assume that client connection with the same name already exists.
					  Probably this is an old connection from the previous session (possible with HTML applications)
					*/
					toDebug("Trying to create new client connection "+ this._connectionName);
					this._connection.connect("_api_" + this._connectionName);
					this._connectedSelf = true;
				}
			}
			catch (e : Error)
			{
        toDebug(e.toString());
        toDebug("Client connection already exists!");
				
				//Try to close existing client connection in previous session
				var cc2 : LocalConnection = new LocalConnection();
				cc.addEventListener(StatusEvent.STATUS, this.handleOutStatusConcurrent, false, 0 , true);
				cc2.send("_api_" + this._connectionName, "closeConnection");
				//Start timer, which will retry creation of client connection
				this._timer.start();
				return;
			}	
			
			/**
			 * If client connection created succesfully, but not proxy not yet connected to this client, we will start timer to monitor, that succefull connection.
			 * 
			 */
			if (!this._connected) {
				if(event)
				{
					//if timer, that we assume that, proxy was connected to another client (previous session) and ask it to reconnect to this client instance
					this._timer.stop();
					this._timer.reset();
					toDebug("Telling proxy to make reconnect...");
					var cc : LocalConnection = new LocalConnection();
					cc.addEventListener(StatusEvent.STATUS, this.handleOutStatusReconnect, false, 0 , true);
					cc.send("_proxy_" + this._connectionName, "makeReconnect");
					
				}
				else
				{
					//start timer on first iteration 
					this._timer.start();
				}
			}
			
		}
		
		private function handleOutStatusReconnect(event : StatusEvent) : void
		{
			if (event.level != StatusEvent.STATUS)
			{
				toDebug("Proxy not responding!");
			}
		}
		
		private function handleOutStatusConcurrent(event : StatusEvent) : void
		{
			if (event.level != StatusEvent.STATUS)
			{
				toDebug("Concurrent client not responding!");
			}
		}
		public function closeConnection() : void
		{
      toDebug("close connection");
			this._connection.close();
		}
		
		public function establishConnection() : void
		{
			toDebug('Client connection established');
			
			this._timer.stop();
			this._timer.reset();
			
			if (!this._connected)
			{
				this._connected = true;
				this.dispatchEvent(new Event(ForticomAPI.CONNECTED));
				
				while (this._eventsPool.length)
				{
					var item : PoolItem = this._eventsPool.shift() as PoolItem;
					this.doSend.apply(this, [item.method].concat(item.args))
				}
			}
		}
		
		public function apiCallBack(methodName : String, result : String, params : String) : void
		{
			this.dispatchEvent(new ApiCallbackEvent(ApiCallbackEvent.CALL_BACK, false, false, methodName, result, params));
		}
		
		private function doSend(method : String, ... rest) : void
		{
			toDebug("Sending " + method + " (" + rest + ") _connected: " + !!(this._connected));
      try
      {
        if (this._connected)
        {
          var cc : LocalConnection = new LocalConnection;
          if (rest.length) {
            cc.send.apply(cc, ["_proxy_" + ForticomAPI.instance._connectionName, method].concat(rest));
          }else {
            cc.send.apply(cc, ["_proxy_" + ForticomAPI.instance._connectionName, method]);
          }
        }
        else {
          this._eventsPool.push(new PoolItem(method, rest));
        }
      } catch(e : *) {
        toDebug('doSend ERROR: ' + e.toString());  
      }
		}
		
		public static function get instance() : ForticomAPI
		{
			ForticomAPI._instance = ForticomAPI._instance ? ForticomAPI._instance : new ForticomAPI(new SingleTonKey);
			return ForticomAPI._instance;
		}
		
		public static function showPermissions(... permissions ) : void
		{
			ForticomAPI.instance.doSend("showPermissions", JSON.serialize(permissions));
		}
		
		public static function showInvite(text : String = null, params : String = null) : void
		{
			ForticomAPI.instance.doSend("showInvite", text, params);
		}
		
		public static function showNotification(text : String, params : String = null) : void
		{
			ForticomAPI.instance.doSend("showNotification", text, params);
		}
		
		public static function showPayment(name : String, description : String, code : String, price : int = -1, options : String = null, attributes : String = null, currency: String = null, callback : String = 'false') : void
		{
			//ForticomAPI.instance.doSend("showPayment", name, description, code, price, options, attributes, currency, callback);
		}
		
		public static function showConfirmation(method : String, userText : String, signature : String) : void
		{
			ForticomAPI.instance.doSend("showConfirmation", method, userText, signature);
		}
		
		public static function setWindowSize(width : int, height : int) : void
		{
			ForticomAPI.instance.doSend("setWindowSize", width.toString(), height.toString());
		}
		
		public static function set connection(name : String) : void
		{
			ForticomAPI.instance._connectionName = name;
			ForticomAPI.instance.tryToCreateConnection();
		}
		
		public static function addEventListener(type : String, listener : Function, useCapture : Boolean = false, priority : int = 0, useWeakReference : Boolean = false) : void
		{
			ForticomAPI.instance.addEventListener(type, listener, useCapture, priority, useWeakReference);
		}
		
		public static function removeEventListener(type : String, listener : Function, useCapture : Boolean = false) : void
		{
			ForticomAPI.instance.removeEventListener(type, listener, useCapture);
		}
		
		public static function send(method : String, ... rest) : void
		{
			toDebug('CLI: Method '+method+", arguments "+rest);
			ForticomAPI.instance.doSend.apply(ForticomAPI.instance, [method].concat(rest))
		}

    public static function toDebug(message : String) : void
    {
      try {
        ExternalInterface.call('window.console.info', '[FAPI] ' +  message.substr(0, 300));
      } catch(e : *) {};
    }
	}

}

internal class SingleTonKey { public function SingleTonKey() { } }
internal class PoolItem
{
	public var method : String;
	public var args : Array;
	
	public function PoolItem($method : String, ... $args)
	{
		this.method = $method;
		this.args = $args;
	}
}
