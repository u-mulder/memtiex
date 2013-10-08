<?php
/**
 *
 *
 * @author u_mulder <m264695502@gmail.com>
 */
class CMailImport {

    private
        $errors;


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
        if (!empty($file_data)) {
            /*if () {


            } else {


            }*/
        } else {
            $this->_addError('Пустой массив входных данных');

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

}
