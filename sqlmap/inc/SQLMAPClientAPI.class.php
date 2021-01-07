<?php

  include("config.php");

  function is_ascii($sourcefile) {
    if (is_file($sourcefile)) {
      $content = str_replace(array("\n", "\r", "\t", "\v", "\b"), '', file_get_contents($sourcefile));
      return ctype_print($content);
    } else {
      return false;
    }
  }

  function is_assoc(array $array) {
    $keys = array_keys($array); 
    return array_keys($keys) !== $keys;
  }


  class SQLMAPClientAPI {
    private $api = API_URL;                        
    public $task_id;
  
    public function __construct() {
//      $this->task_id = $this->generateNewTaskID();
    }

    public function generateNewTaskID() {
      $json = json_decode(file_get_contents($this->api . "task/new"), true);
      if(($json['success'] == "true") && (trim($json['taskid']) != "")) {
        return trim($json['taskid']);
      }
      return NULL;
    }

    public function deleteTaskID($id) {
      $json = json_decode(file_get_contents($this->api . "task/" . $id . "/delete"), true);
      if($json['success'] == "true") {
        return true;
      }
      return false;
    }

    public function adminListTasks($adminid) {
      $json = json_decode(file_get_contents($this->api . "admin/" . $adminid . "/list"), true);
      if($json['success'] == "true") {
        return array('tasks_num' => $json['tasks_num'], 'tasks' => $json['tasks']);
      }
      return false;
    }


    public function adminFlushTasks($adminid) {
      $json = json_decode(file_get_contents($this->api . "admin/" . $adminid . "/flush"), true);
      if($json['success'] == "true") {
        return true;
      }
      return false;
    }

    public function listOptions($taskid) {
      $json = json_decode(file_get_contents($this->api . "option/" . $taskid . "/list"), true);
      if($json['success'] == "true") {
        return $json;
      }
      return false;
    }
    public function getOptionValue($taskid, $optstr) {
      if((strtolower(trim($optstr)) != "evalcode") && (strtolower(trim($optstr)) != "eval")) {
        $opts = array(
          'http'=> array(
            'method'=>"POST",
            'header'=>"Content-Type: application/json\r\n",
            'content' => '{"option":"' . trim($optstr) . '"}',
            'timeout' => 60
          )
        );
        $context = stream_context_create($opts);
        $json = json_decode(file_get_contents($this->api . "option/" . $taskid . "/get", false, $context), true);
        if($json['success'] == "true") {
          return $json[$optstr];
        }
      }
      return false;
    }

    public function setOptionValue($taskid, $optstr, $optvalue, $integer=false) {
      if((strtolower(trim($optstr)) != "evalcode") && (strtolower(trim($optstr)) != "eval")) {
        if(!$integer) {
          $opts = array(
            'http'=> array(
              'method'=>"POST",
              'header'=>"Content-Type: application/json\r\n",
              'content' => '{"' . trim($optstr) . '":"' . trim($optvalue) . '"}',
              'timeout' => 60
            )
          );
        } else {
          $opts = array(
            'http'=> array(
              'method'=>"POST",
              'header'=>"Content-Type: application/json\r\n",
              'content' => '{"' . trim($optstr) . '":' . trim($optvalue) . '}',
              'timeout' => 60
            )
          );
        }
        $context = stream_context_create($opts);
        $json = json_decode(file_get_contents($this->api . "option/" . $taskid . "/set", false, $context), true);
        if($json['success'] == "true") {
          return true;
        }
      }
      return false;
    }

    public function startScan($taskid) {
      $opts = array(
        'http'=> array(
          'method'=>"POST",
          'header'=>"Content-Type: application/json\r\n",
          'content' => '{ "url":"' . trim($this->getOptionValue($taskid, "url")) . '"}',
          'timeout' => 60
        )
      );
      $context = stream_context_create($opts);
      $json = json_decode(file_get_contents($this->api . "scan/" . $taskid . "/start", false, $context), true);
      if($json['success'] == 1) {
        return $json['engineid'];
      }
      return false;
    }


    public function stopScan($taskid) {
      $json = json_decode(file_get_contents($this->api . "scan/" . $taskid . "/stop"), true);
      if($json['success'] == 1) {
        return true;
      }
      return false;
    }


    public function killScan($taskid) {
      $json = json_decode(file_get_contents($this->api . "scan/" . $taskid . "/kill"), true);
      if($json['success'] == 1) {
        return true;
      }
      return false;
    }


    public function checkScanStatus($taskid) {
      $json = json_decode(file_get_contents($this->api . "scan/" . $taskid . "/status"), true);
      if($json['success'] == 1) {
        return array("status" => $json['status'], "code" => $json['returncode']);
      }
      return false;
    }


    public function getScanData($taskid) {
      $json = json_decode(file_get_contents($this->api . "scan/" . $taskid . "/data"), true);
      if($json['success'] == 1) {
        return array("data" => $json['data'], "error" => $json['error']);
      }
      return false;
    }


    public function reviewScanLogPartial($taskid, $start, $end) {
      $json = json_decode(file_get_contents($this->api . "scan/" . $taskid . "/log/" . $start . "/" . $end), true);
      if($json['success'] == 1) {
        return $json['log'];
      }
      return false;
    }

    public function reviewScanLogFull($taskid) {
      $json = json_decode(file_get_contents($this->api . "scan/" . $taskid . "/log"), true);
      if($json['success'] == 1) {
        return $json['log'];
      }
      return false;
    }


    public function downloadTargetFile($taskid, $target, $filename) {
      if((!preg_match("#..|%2e%2e|\x2e\x2e|0x2e0x2e#", $target)) && (!preg_match("#..|%2e%2e|\x2e\x2e|0x2e0x2e#", $filename))) {
        $json = json_decode(file_get_contents($this->api . "download/" . $taskid . "/" . $target . "/" . $filename), true);
        if($json['success'] == "true") {
          return $json['file'];
        }
      }
      return false;
    }
  }

?>
