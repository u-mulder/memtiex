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
        $export_filename;

    public function __construct() {
        $this->errors = array();
        $this->events_data = array();
    }

    public function execute($event_types) {
        $t = sizeof($event_types);
        if (0 < $t) {
            $i = 0;
            for(; $i < $t; $i++) {
                $et_name = trim($event_types[$i]);
                if ('' != $et_name) {
                    $this->_getEventTypeData($et_name);
                    echo'<pre>',print_r($this->events_data),'</pre>';
                }
            }
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
        while ($row = $db_items->Fetch())
            $this->events_data[] = array(
                'lid' => $row['LID'],
                'name' => $row['NAME'],
                'description' => $row['DESCRIPTION'],
                'sort' => $row['SORT'],
                'messages' => $this->_getEventMessages($et_name)
            );
    }


    protected function _getEventMessages($et_name) {
        $et_name = trim($et_name);
        $result = array();

        if ('' != $et_name) {


        }

        return $result;
    }

}
