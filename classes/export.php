<?php
/**
 *
 *
 * @author u_mulder <m264695502@gmail.com>
 */
class CMailExport {

    private
        $errors,
        $events_data,
        $export_filename,
        $_additional_message_fields;

    public function __construct() {
        $this->errors = array();
        $this->events_data = array();
        $this->_additional_message_fields = array(
            'BCC', 'REPLY_TO', 'CC', 'IN_REPLY_TO', 'PRIORITY',
            'FIELD1_NAME', 'FIELD1_VALUE', 'FIELD2_NAME', 'FIELD2_VALUE'
        );
    }

    public function execute($event_types) {
        $t = sizeof($event_types);
        if (0 < $t) {
            $i = 0;
            for(; $i < $t; $i++) {
                $et_name = trim($event_types[$i]);
                if ('' != $et_name)
                    $this->_getEventTypeData($et_name);
            }
            echo'<pre>',print_r($this->events_data),'</pre>';
            die();

            $this->_export();
        } else {
            $this->_addError('Не указаны коды почтовых событий.');
        }
    }


    public function getErrors() {
        return 0 < sizeof($this->errors)? $this->errors : false;
    }


    protected function _addError($err_str='') {
        $err_str = trim($err_str);
        if ('' != $err_str)
            $this->errors[] = $err_str;
    }


    protected function _getEventTypeData($et_name) {
        $db_items = CEventType::GetList(array('TYPE_ID' => $et_name));
        while ($row = $db_items->Fetch()) {
            $code = $row['EVENT_NAME'];
            if (!isset($this->events_data[$code]))
                $this->events_data[$code] = array(
                    'events' => array(),
                    'messages' => array()
                );
            $this->events_data[$code]['events'][] = array(
                'lid' => $row['LID'],
                'name' => $row['NAME'],
                'description' => $row['DESCRIPTION'],
                'sort' => $row['SORT'],
            );

            if (empty($this->events_data[$code]['messages']))
                $this->events_data[$code]['messages']
                    = self::_getEventMessages($code);
        }
    }


    protected function _getEventMessages($et_name) {
        $et_name = trim($et_name);
        $result = array();

        if ('' != $et_name) {
            $db_items = CEventMessage::GetList(
                ($by = 'event_name'),
                ($order = 'asc'),
                array('TYPE_ID' => $et_name)
            );
            while ($row = $db_items->Fetch()) {
                $message_data = array(
                    'ACTIVE' => $row['ACTIVE'],
                    'LID' => $row['LID'],
                    'SITE_ID' => $row['SITE_ID'],
                    'EMAIL_FROM' => $row['EMAIL_FROM'],
                    'EMAIL_TO' => $row['EMAIL_TO'],
                    'SUBJECT' => $row['SUBJECT'],
                    'MESSAGE' => $row['MESSAGE'],
                    'BODY_TYPE' => $row['BODY_TYPE']
                );
                foreach ($this->_additional_message_fields as $field)
                    if ('' != $row[$field])
                       $message_data[$field] = $row[$field];

                $result[] = $message_data;
            }
        }

        return $result;
    }


    protected function _export() {
        $t = sizeof($this->events_data);
        if (0 < $t) {
            /*echo'<pre>Export:',print_r($this->events_data),'</pre>';




            $i = 0;
            for (; $i < $t; $i++) {


            }*/
        } else {
            $this->_addError('Нет данных для импорта.');
        }
    }

}
