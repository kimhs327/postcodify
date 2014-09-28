<?php

/**
 *  Postcodify - 도로명주소 우편번호 검색 프로그램 (인덱서)
 * 
 *  Copyright (c) 2014, Kijin Sung <root@poesis.kr>
 * 
 *  이 프로그램은 자유 소프트웨어입니다. 이 소프트웨어의 피양도자는 자유
 *  소프트웨어 재단이 공표한 GNU 약소 일반 공중 사용 허가서 (GNU LGPL) 제3판
 *  또는 그 이후의 판을 임의로 선택하여, 그 규정에 따라 이 프로그램을
 *  개작하거나 재배포할 수 있습니다.
 * 
 *  이 프로그램은 유용하게 사용될 수 있으리라는 희망에서 배포되고 있지만,
 *  특정한 목적에 맞는 적합성 여부나 판매용으로 사용할 수 있으리라는 묵시적인
 *  보증을 포함한 어떠한 형태의 보증도 제공하지 않습니다. 보다 자세한 사항에
 *  대해서는 GNU 약소 일반 공중 사용 허가서를 참고하시기 바랍니다.
 * 
 *  GNU 약소 일반 공중 사용 허가서는 이 프로그램과 함께 제공됩니다.
 *  만약 허가서가 누락되어 있다면 자유 소프트웨어 재단으로 문의하시기 바랍니다.
 */

class Postcodify_Indexer_Parser_Pobox extends Postcodify_Indexer_ZipReader
{
    // 사서함 갯수를 세는 변수.
    
    protected $_count = 0;
    
    // 한 줄을 읽어 반환한다.
    
    public function read_line($delimiter = '|')
    {
        // 데이터를 읽는다.
        
        $line = parent::read_line($delimiter);
        if ($line === false || count($line) < 10) return false;
        
        // 상세 데이터를 읽어들인다.
        
        $sido = trim($line[2]);
        $sigungu = trim($line[3]);
        $eupmyeon = trim($line[4]);
        $pobox_name = trim($line[5]);
        
        $range_start_major = trim($line[6]); if (!$range_start_major) $range_start_major = null;
        $range_start_minor = trim($line[7]); if (!$range_start_minor) $range_start_minor = null;
        $range_end_major = trim($line[8]); if (!$range_end_major) $range_end_major = null;
        $range_end_minor = trim($line[9]); if (!$range_end_minor) $range_end_minor = null;
        
        // 특별시/광역시 아래의 자치구와 행정시 아래의 일반구를 구분한다.
        
        if (($pos = strpos($sigungu, ' ')) !== false)
        {
            $ilbangu = substr($sigungu, $pos + 1);
            $sigungu = substr($sigungu, 0, $pos);
        }
        else
        {
            $ilbangu = null;
        }
        
        // 시군구가 없는 경우(세종시)를 처리한다.
        
        if ($sigungu === '')
        {
            $sigungu = null;
        }
        
        // 관리번호를 생성한다.
        
        $address_id = '9999999999999999999' . str_pad(++$this->_count + 1, 6, '0', STR_PAD_LEFT);
        
        // 데이터를 정리하여 반환한다.
        
        return (object)array(
            'address_id' => $address_id,
            'postcode6' => trim($line[0]),
            'road_id' => '999999999999',
            'road_section' => '99',
            'sido' => $sido,
            'sigungu' => $sigungu,
            'ilbangu' => $ilbangu,
            'eupmyeon' => $eupmyeon,
            'pobox_name' => $pobox_name,
            'range_start_major' => $range_start_major,
            'range_start_minor' => $range_start_minor,
            'range_end_major' => $range_end_major,
            'range_end_minor' => $range_end_minor,
        );
    }
}