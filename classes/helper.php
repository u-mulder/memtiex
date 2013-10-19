<?php
/**
 *
 *
 * @author u_mulder <m264695502@gmail.com>
 */
class CMailHelper {

    const
        NO_DESCRIPTION_SET = '(без описания)',
        ALLOWED_DESCRIPTION_LENGTH = 64;

    public static function getEventTypes() {
        $result = array();

        $db_items = CEventType::GetList(
            array(),
            array('EVENT_NAME' => 'ASC')
        );
        while ($row = $db_items->Fetch()) {
            $event_name = trim($row['EVENT_NAME']);
            if (!isset($result[$event_name]))
                $result[$event_name][$row['LID']] = array();

            $result[$event_name][$row['LID']] = array(
                'NAME' => $row['NAME'],
                'DESCRIPTION' =>
                    self::_getDescription($row['DESCRIPTION']),
                'SORT' => $row['SORT'],
                'ID' => $row['ID']
            );
        }

        return $result;
    }


    protected static function _getDescription($str) {
        $str = trim($str);

        return '' == $str? self::NO_DESCRIPTION_SET :
            mb_substr($str, 0, self::ALLOWED_DESCRIPTION_LENGTH);
    }


    public static function convertCharset($str, $charset_from, $charset_to) {
        $result = $str;

        if ($charset_from != $charset_to) {
            global $APPLICATION;
            $result = $APPLICATION->ConvertCharset($str, $charset_from, $charset_to);
        }

        return $result;
    }

}
