package 
{
  import flash.external.ExternalInterface;
	import com.serialization.json.JSON;
  import mx.controls.Alert;

  public class JSPort
  {
    private var callback_object : String;

    public function JSPort(_callback_object : String)
    {
      callback_object = _callback_object;
    }

    public function callToJs(name : String, ...args) : void
    {
      name = callback_object + '.' + name;
      if(ExternalInterface.available) 
      {
        args.unshift(name);
        ExternalInterface.call.apply(ExternalInterface.call, args);
      }
    }

    public function dump(v : *) : String
    {
      return JSON.serialize(v);  
    }

    public function toDebug(message : String) : void
    {
      try {
        ExternalInterface.call('window.console.info', '[bit.OdnApi] ' +  message.substr(0, 300));
      } catch(e : *) {};
    }

    public function addExternalCallback(name : String, callback : Function) : Boolean
    {
      if(!ExternalInterface.available) 
        return false;
      try 
      {
        toDebug('add callback ' + name);
        ExternalInterface.addCallback(name, function(...args) : * {
          toDebug('call ' + name + ' ('+ JSON.serialize(args) + ')');
          try 
          {
            return callback.apply(callback, args);
          } catch(e : *) {
            toDebug('error call ' + name + ': ' + e.toString());              
          }
        });
        return true;
      } catch (error : *) { };
      return false;
    }
  }
}
