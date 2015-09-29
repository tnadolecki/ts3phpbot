<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* set error reporting levels */ 
error_reporting(E_ALL | E_STRICT); 

/* set default timezone */ 
date_default_timezone_set("Europe/Dublin"); 

/* deny browser access */ 
if(php_sapi_name() != "cli") 
{ 
  die("ERROR, access denied"); 
} 

/* load required files */ 
require_once("config.php"); 

/* load framework library */ 
require_once("libraries/TeamSpeak3/TeamSpeak3.php"); 

/* initialize */ 
TeamSpeak3::init(); 

try 
{ 
  /* subscribe to various events */ 
  //TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyEvent", "onEvent"); 
  TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyCliententerview", "onCliententerview"); 
  TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyClientleftview", "onClientleftview"); 
  TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyClientmoved", "onClientmoved"); 
  TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyServeredited", "onServeredited"); 
  TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyChanneledited", "onChanneledited"); 
  TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyChannelmoved", "onChannelmoved"); 
  TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyChannelcreated", "onChannelcreated"); 
  TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyChanneldeleted", "onChanneldeleted"); 
  TeamSpeak3_Helper_Signal::getInstance()->subscribe("serverqueryConnected", "onConnect"); 
  TeamSpeak3_Helper_Signal::getInstance()->subscribe("serverqueryCommandStarted", "onCommand"); 
  TeamSpeak3_Helper_Signal::getInstance()->subscribe("serverqueryWaitTimeout", "onTimeout"); 
  TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyLogin", "onLogin"); 
  TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyTextmessage", "onTextmessage"); 
  TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyServerselected", "onSelect"); 
  TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyTokencreated", "onToken"); 

  /* connect to server, login and get TeamSpeak3_Node_Host object by URI */ 
  $ts3 = TeamSpeak3::factory("serverquery://" . $cfg["user"] . ":" . $cfg["pass"] . "@" . $cfg["host"] . ":" . $cfg["query"] . "/?server_port=" . $cfg["voice"] . "&blocking=0"); 
   
  /* register for all events available */ 
  $ts3->notifyRegister("server"); 
  $ts3->notifyRegister("channel"); 
  $ts3->notifyRegister("textserver"); 
  $ts3->notifyRegister("textchannel"); 
  $ts3->notifyRegister("textprivate"); 

    while(1) 
    { 
    //...<some logging code>... 
    $ts3->getAdapter()->wait(); 
    }   
} 
catch(Exception $e) 
{ 
  die("[ERROR]  " . $e->getMessage() . "\n"); 
} 

function onTextmessage(TeamSpeak3_Adapter_ServerQuery_Event $event, TeamSpeak3_Node_Host $host) 
{ 
    echo "[SIGNAL] client " . $event["invokername"] . " sent textmessage: " . $event["msg"] . "\n"; 
}

function onLogin(TeamSpeak3_Adapter_ServerQuery_Event $event, TeamSpeak3_Node_Host $host) 
{ 
    echo "[SIGNAL] client " . $event["invokername"] . " sent textmessage: " . $event["msg"] . "\n"; 
}