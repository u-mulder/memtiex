<?php
/**
 *
 *
 * @author u_mulder <m264695502@gmail.com>
 */
class CMailImport {

    private
        $errors,
        $obj_event_type = false,
        $obj_event_message = false;


    public function __construct() {
        $this->errors = array();
        /*$this->events_data = array();
        $this->_additional_message_fields = array(
            'BCC', 'REPLY_TO', 'CC', 'IN_REPLY_TO', 'PRIORITY',
            'FIELD1_NAME', 'FIELD1_VALUE', 'FIELD2_NAME', 'FIELD2_VALUE'
        );
        $this->_export_filename = CMailTags::EXPORT_FILENAME;*/
    }


    public function execute($file_data) {
        /*if (!empty($file_data))*/ {
        
            $xml = new SimpleXMLElement('t.xml', 0, true);
            //$xml = simplexml_load_file('t.xml');
            if ($xml) {
                
                foreach ($xml as $event) {
                    $message_add_allowed = true;
                    $event_name = strval($event->{CMailTags::EVENT_CODE});
                    foreach ($event->{CMailTags::EVENT_ITEM} as $event_item) {
                    
                        echo '<pre>',print_r($event_item),'</pre>';
                        echo '<pre>',print_r($event_item->attributes()),'</pre>';
                        //echo '<pre>',print_r($et_data),'</pre>';
                        
                        $res = $this->_addEventType(array(
                            'EVENT_NAME' => $event_name,
                            'LID' => '',
                            'NAME' => strval($event_item->{CMailTags::EVENT_NAME}),
                            'DESCRIPTION' => strval($event_item->{CMailTags::EVENT_DESCR}),
                            'SORT' => '',
                        ));
                        if (!$res)
                            $message_add_allowed = false;
                    }
                    if ($message_add_allowed) {
                        foreach ($event->{CMailTags::MESSAGE} as $msg) {
                        
                        
                            $this->_addEventMessage(array(
                            
                            
                            ));
                        }
                    
                    }
                }
            } else {
                echo '321321';
            }
        } /*else {
            $this->_addError('Пустой массив входных данных');

        }*/
        
        die('232323');
    }


    public function getErrors() {
        return 0 < sizeof($this->errors)? $this->errors : false;
    }


    protected function _addEventType(array $et_data) {
        $result = false;
    
        if (!empty($et_data)) {
            if (false === $this->obj_event_type )
                $this->obj_event_type = new CEventType;
            
            $result = $this->obj_event_type->Add($et_data);
            if (!$result)
                $this->_addError('Ошибка добавления почтового события: '
                    . $this->obj_event_type->LAST_ERROR);
        }
        
        return $result;
    }

    
    protected function _addEventMessage(array $em_data) {
        if (!empty($em_data)) {
            if (false === $this->obj_event_message)
                $this->obj_event_message = new CEventMessage;
                
            $res = $this->obj_event_message->Add($em_data);
            if ($res) {
            
            } else {
                $this->_addError('Ошибка добавления почтового шаблона: '
                    . $this->obj_event_message->LAST_ERROR);
            }
        }
    
    }
    

    protected function _addError($err_str='') {
        $err_str = trim($err_str);
        if ('' != $err_str)
            $this->errors[] = $err_str;
    }

}
