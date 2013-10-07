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
        $_export_filename,
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
            $this->_export();
            die('(((');

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


    protected function _export() {  // TODO
        if (0 < sizeof($this->events_data)) {
            $xml = new SimpleXMLElement('<' . '?xml version="1.0" encoding="UTF-8"?' . '><events></events>');
            if ($xml !== false) {
                foreach ($this->events_data as $code => $data) {
                    $event = $xml->addChild(CMailTags::EVENT);
                    $event->addChild(CMailTags::EVENT_CODE, $code);
                    foreach ($data['events'] as $lid_event) {
                        $lid_event = $event->addChild(CMailTags::EVENT_ITEM);
                        $lid_event->addAttribute(CMailTags::SORT, $lid_event['sort']);
                        $lid_event->addAttribute(CMailTags::EVENT_LID, $lid_event['lid']);
                        $lid_event->addChild(CMailTags::EVENT_NAME, $lid_event['name']);
                        $lid_event->addChild(CMailTags::EVENT_DESCR, $lid_event['description']);
                    }
                    if (!empty($data['messages'])) {
                        foreach ($data['messages'] as $message) {
                            $message = $event->addChild(CMailTags::MESSAGE);
                            $message->addAttribute(CMailTags::ACTIVE, $message['ACTIVE']);
                            $message->addAttribute(CMailTags::LID, $message['LID']);
                            $message->addAttribute(CMailTags::SITE_ID, $message['SITE_ID']);
                            $message->addChild(CMailTags::EMAIL_FROM, $message['EMAIL_FROM']);
                            $message->addChild(CMailTags::EMAIL_TO, $message['EMAIL_TO']);
                            $message->addChild(CMailTags::SUBJECT, $message['SUBJECT']);
                            $message->addChild(CMailTags::MESSAGE_TEXT, $message['MESSAGE']);
                            $message->addChild(CMailTags::BODY_TYPE, $message['BODY_TYPE']);
                            foreach ($this->_additional_message_fields as $field)
                                if (array_key_exists($field, $message))
                                    $message->addChild($field, $message[$field]);
                        }
                    }
                }
                $xml->asXML('t.xml');
            } else {
                $this->_addError('Ошибка создания файла XML');
            }
        } else {
            $this->_addError('Нет данных для импорта.');
        }
    }

}
