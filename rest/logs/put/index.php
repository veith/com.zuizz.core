<?php
/** @var $this ZUFEATURE */
/** @var $REST ZUREST */
$REST =& $this->rest;
$id = $REST->decodeID($this->values['identifier']);

try {
    $logs = ORM::for_table('log')->find_one($id);
    if ($logs) {
        $logs->set('label',$this->values['label']);
        $logs->save();
    } else {
        throw new Exception('no object with id ' . $id);
    }
    ZU::header(202);

} catch (Exception $e) {
    ZU::header(404);

    $this->data['message'] = $e->getMessage() ;
}


// set default mimetype
if (!$this->mimetype) {
    $this->mimetype = 'json';
}

switch ($this->mimetype) {
    case "json":
        header('Content-type: application/json');
        $this->contentbuffer = json_encode($this->data);
        break;
    case "xml":
        header('Content-type: application/xml');
        ZU::load_class('lalit.array2xml', 'xml', true);
        $xml = Array2XML::createXML('machines', $this->data);
        $this->contentbuffer = $xml->saveXML();
        break;
}