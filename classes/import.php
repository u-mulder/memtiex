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
        $obj_event_message = false,
        $_additional_message_fields,
        $_import_filename;

    public function __construct() {
        $this->errors = array();
        $this->_additional_message_fields = array(
            'BCC', 'REPLY_TO', 'CC', 'IN_REPLY_TO', 'PRIORITY',
            'FIELD1_NAME', 'FIELD1_VALUE', 'FIELD2_NAME', 'FIELD2_VALUE'
        );
        $this->_import_filename = CMailTags::EXPORT_FILENAME;
    }


    public function execute($file_data) {
        /*if (!empty($file_data))*/ {
            $xml = new SimpleXMLElement($this->_import_filename, 0, true);
            if ($xml) {
                foreach ($xml as $event) {
                    $message_add_allowed = true;
                    $event_name = strval($event->{CMailTags::EVENT_CODE});
                    foreach ($event->{CMailTags::EVENT_ITEM} as $event_item) {
                        $attributes = $event_item->attributes();
                        $lid = strval($attributes->{CMailTags::EVENT_LID});
                        if ('' == $lid)
                            $lid = 'ru';
                        $sort = strval($attributes->{CMailTags::SORT});
                        if ('' == $sort)
                            $sort = '150';
                        $res = $this->_addEventType(array(
                            'EVENT_NAME' => $event_name,
                            'LID' => $lid,
                            'NAME' => strval($event_item->{CMailTags::EVENT_NAME}),
                            'DESCRIPTION' => strval($event_item->{CMailTags::EVENT_DESCR}),
                            'SORT' => $sort,
                        ));
                        if (!$res)
                            $message_add_allowed = false;
                    }

                    var_dump($message_add_allowed);
                    $message_add_allowed = true;

                    if ($message_add_allowed) {
                        foreach ($event->{CMailTags::MESSAGE} as $msg) {
                            $attributes = $msg->attributes();
                            $active = strval($attributes->{CMailTags::ACTIVE});
                            /*$ = strval($msg->{CMailTag::});
                            $ = strval($msg->{CMailTag::});*/
                            $msg_array = array(
                                'ACTIVE' => $active,
                                //'LID' => $lid,    // TODO
                                'EVENT_NAME' => $event_name,
                                'EMAIL_FROM' => strval($msg->{CMailTags::EMAIL_FROM}),
                                'EMAIL_TO ' => strval($msg->{CMailTags::EMAIL_TO}),
                                'SUBJECT' => strval($msg->{CMailTags::SUBJECT}),
                                'MESSAGE' => strval($msg->{CMailTags::MESSAGE_TEXT}),
                                'BODY_TYPE' => strval($msg->{CMailTags::BODY_TYPE}),
                            );
                            foreach ($this->_additional_message_fields as $field) {
                                //if ()


                            }
                            $this->_addEventMessage($msg_array);
                        }
                    }
                }
            } else {
                $this->_addError('Не удалось создать XML-объект из загруженного файла');
            }
        } /*else {
            $this->_addError('Пустой массив входных данных');

        }*/

        echo'<pre>',print_r($this->errors),'</pre>';
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
            /*if (false === $this->obj_event_message)
                $this->obj_event_message = new CEventMessage;

            $res = $this->obj_event_message->Add($em_data);
            if ($res) {

            } else {
                $this->_addError('Ошибка добавления почтового шаблона: '
                    . $this->obj_event_message->LAST_ERROR);
            }*/

            echo'<pre>',print_r($em_data),'</pre>';

        }

    }


    protected function _addError($err_str='') {
        $err_str = trim($err_str);
        if ('' != $err_str)
            $this->errors[] = $err_str;
    }

}
