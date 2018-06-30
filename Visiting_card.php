<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @file
* A class to generate vCards for contact data.
*/
class Visiting_card
{
/**
* vcard variables
**/
protected $ci;
protected $log;
protected $data;              //array of this vcard's contact data
private $filename;             //filename for download file naming
private $class;             //PUBLIC, PRIVATE, CONFIDENTIAL
private $revision_date;     //vCard Date
private $card;                //vCard String
/**
* __construct
**/
public function __construct()
{
$this->ci =& get_instance();
//$ci->load->library('session');
$this->ci->load->library('zip');
}
/**
*The Vcards class constructor. You can set some defaults here if desired.
*
*/    
function Vcard($data = false) {
$this->log = "New vcard() called<br />";
$this->data = array(
"display_name"=>null
,"first_name"=>null
,"last_name"=>null
,"additional_name"=>null
,"name_prefix"=>null
,"name_suffix"=>null
,"nickname"=>null
,"title"=>null
,"role"=>null
,"department"=>null
,"company"=>null
,"work_po_box"=>null
,"work_extended_address"=>null
,"work_address"=>null
,"work_city"=>null
,"work_state"=>null
,"work_postal_code"=>null
,"work_country"=>null
,"home_po_box"=>null
,"home_extended_address"=>null
,"home_address"=>null
,"home_city"=>null
,"home_state"=>null
,"home_postal_code"=>null
,"home_country"=>null
,"office_tel"=>null
,"home_tel"=>null
,"cell_tel"=>null
,"mobile"=>null
,"mobile_2"=>null
,"fax_tel"=>null
,"pager_tel"=>null
,"email1"=>null
,"email2"=>null
,"email3"=>null
,"url"=>null
,"url1"=>null
,"url2"=>null
,"url3"=>null
,"url4"=>null
,"url5"=>null
,"url6"=>null
,"url7"=>null
,"url8"=>null
,"url9"=>null
,"url10"=>null
,"url11"=>null
,"url12"=>null
,"url13"=>null
,"url14"=>null
,"url15"=>null
,"photo"=>null
,"birthday"=>null
,"timezone"=>null
,"sort_string"=>null
,"note"=>null
);
if(is_array($data))
{
foreach($data as $item => $value)
{
$this->data[$item] = $value;
}
}
return true;
}
/**
*The Reload method. This metod update the DATA array content.
*
*/    
function reload($data = false) {
$this->log = "reload() called<br />";
$this->data = array(
"display_name"=>null
,"first_name"=>null
,"last_name"=>null
,"additional_name"=>null
,"name_prefix"=>null
,"name_suffix"=>null
,"nickname"=>null
,"title"=>null
,"role"=>null
,"department"=>null
,"company"=>null
,"work_po_box"=>null
,"work_extended_address"=>null
,"work_address"=>null
,"work_city"=>null
,"work_state"=>null
,"work_postal_code"=>null
,"work_country"=>null
,"home_po_box"=>null
,"home_extended_address"=>null
,"home_address"=>null
,"home_city"=>null
,"home_state"=>null
,"home_postal_code"=>null
,"home_country"=>null
,"office_tel"=>null
,"home_tel"=>null
,"cell_tel"=>null
,"mobile"=>null
,"mobile_2"=>null
,"fax_tel"=>null
,"pager_tel"=>null
,"email1"=>null
,"email2"=>null
,"email3"=>null
,"url"=>null
,"url1"=>null
,"url2"=>null
,"url3"=>null
,"url4"=>null
,"url5"=>null
,"url6"=>null
,"url7"=>null
,"url8"=>null
,"url9"=>null
,"url10"=>null
,"url11"=>null
,"url12"=>null
,"url13"=>null
,"url14"=>null
,"url15"=>null
,"photo"=>null
,"birthday"=>null
,"timezone"=>null
,"sort_string"=>null
,"note"=>null
);
if(is_array($data))
{
foreach($data as $item => $value)
{
$this->data[$item] = $value;
}
}
$this->build();
return $this->card;
}
/**
* Build  method checks all the values, builds appropriate defaults for
* missing values, generates the vcard data string
*
* @param 
* @return VCF file
*/   
function build() {
$this->log .= "vcard build() called<br />";
/*
For many of the values, if they are not passed in, we set defaults or
build them based on other values.
*/
if (!$this->class) { $this->class = "PUBLIC"; }
if (!$this->data['display_name']) {
$this->data['display_name'] = trim($this->data['first_name']." ".$this->data['last_name']);
}
if (!$this->data['sort_string']) { $this->data['sort_string'] = $this->data['last_name']; }
if (!$this->data['sort_string']) { $this->data['sort_string'] = $this->data['company']; }
if (!$this->data['timezone']) { $this->data['timezone'] = date("O"); }
if (!$this->revision_date) { $this->revision_date = date('Y-m-d H:i:s'); }
$this->card = "BEGIN:VCARD\r\n";
$this->card .= "VERSION:3.0\r\n";
$this->card .= "CLASS:".$this->class."\r\n";
$this->card .= "PRODID:-//Vcard Extended Class from bizinfosystems@gmail.com//NONSGML Version 1//EN\r\n";
$this->card .= "REV:".$this->revision_date."\r\n";
$this->card .= "FN:".$this->data['display_name']."\r\n";
$this->card .= "N:"
.$this->data['last_name'].";"
.$this->data['first_name'].";"
.$this->data['additional_name'].";"
.$this->data['name_prefix'].";"
.$this->data['name_suffix']."\r\n";
if ($this->data['nickname']) { $this->card .= "NICKNAME:".$this->data['nickname']."\r\n"; }
if ($this->data['title']) { $this->card .= "TITLE:".$this->data['title']."\r\n"; }
if ($this->data['company']) { $this->card .= "ORG:".$this->data['company']; }
if ($this->data['department']) { $this->card .= ";".$this->data['department']; }
$this->card .= "\r\n";
if ($this->data['work_po_box']
|| $this->data['work_extended_address']
|| $this->data['work_address']
|| $this->data['work_city']
|| $this->data['work_state']
|| $this->data['work_postal_code']
|| $this->data['work_country']) {
$this->card .= "ADR;TYPE=work:"
.$this->data['work_po_box'].";"
.$this->data['work_extended_address'].";"
.$this->data['work_address'].";"
.$this->data['work_city'].";"
.$this->data['work_state'].";"
.$this->data['work_postal_code'].";"
.$this->data['work_country']."\r\n";
}
if ($this->data['home_po_box']
|| $this->data['home_extended_address']
|| $this->data['home_address']
|| $this->data['home_city']
|| $this->data['home_state']
|| $this->data['home_postal_code']
|| $this->data['home_country']) {
$this->card .= "ADR;TYPE=home:"
.$this->data['home_po_box'].";"
.$this->data['home_extended_address'].";"
.$this->data['home_address'].";"
.$this->data['home_city'].";"
.$this->data['home_state'].";"
.$this->data['home_postal_code'].";"
.$this->data['home_country']."\r\n";
}
if ($this->data['email1']) { $this->card .= "EMAIL;TYPE=Work,pref:".$this->data['email1']."\r\n"; }
if ($this->data['email2']) { $this->card .= "EMAIL;TYPE=Office Email:".$this->data['email2']."\r\n"; }
if ($this->data['email3']) { $this->card .= "EMAIL;TYPE=Personnel Email:".$this->data['email3']."\r\n"; }
if ($this->data['cell_tel']) { $this->card .= "TEL;TYPE=Work,voice:".$this->data['cell_tel']."\r\n"; }      
if ($this->data['office_tel']) { $this->card .= "TEL;TYPE=Work,voice:".$this->data['office_tel']."\r\n"; }
if ($this->data['home_tel']) { $this->card .= "TEL;TYPE=Home,voice:".$this->data['home_tel']."\r\n"; }
if ($this->data['mobile']) { $this->card .= "TEL;TYPE=X-Mobile,voice:".$this->data['mobile']."\r\n"; }
if ($this->data['mobile_2']) { $this->card .= "TEL;TYPE=X-Mobile,voice:".$this->data['mobile_2']."\r\n"; }
if ($this->data['fax_tel']) { $this->card .= "TEL;TYPE=work,fax:".$this->data['fax_tel']."\r\n"; }
if ($this->data['pager_tel']) { $this->card .= "TEL;TYPE=work,pager:".$this->data['pager_tel']."\r\n"; }
// URL
if ($this->data['url']) { $this->card .= "URL;TYPE=Web Visiting Card:".$this->data['url']."\r\n"; }
if ($this->data['url1']) { $this->card .= "URL;TYPE=Webpage:".$this->data['url1']."\r\n"; }
if ($this->data['url2']) { $this->card .= "URL;TYPE=Listing:".$this->data['url2']."\r\n"; }
if ($this->data['url3']) { $this->card .= "URL;TYPE=Website:".$this->data['url3']."\r\n"; }
if ($this->data['url4']) { $this->card .= "URL;TYPE=Appointment:".$this->data['url4']."\r\n"; }

if ($this->data['url5']) { $this->card .= "URL;TYPE=Googlemap:".$this->data['url5']."\r\n"; }
if ($this->data['url6']) { $this->card .= "URL;TYPE=Facebook:".$this->data['url6']."\r\n"; }
if ($this->data['url7']) { $this->card .= "URL;TYPE=Twitter:".$this->data['url7']."\r\n"; }
if ($this->data['url8']) { $this->card .= "URL;TYPE=Linkedin:".$this->data['url8']."\r\n"; }
if ($this->data['url9']) { $this->card .= "URL;TYPE=Googleplus:".$this->data['url9']."\r\n"; }
if ($this->data['url10']) { $this->card .= "URL;TYPE=Instagram:".$this->data['url10']."\r\n"; }
if ($this->data['url11']) { $this->card .= "URL;TYPE=Webpage:".$this->data['url11']."\r\n"; }
if ($this->data['url12']) { $this->card .= "URL;TYPE=Webpage:".$this->data['url12']."\r\n"; }
if ($this->data['url13']) { $this->card .= "URL;TYPE=Webpage:".$this->data['url13']."\r\n"; }
if ($this->data['url14']) { $this->card .= "URL;TYPE=Webpage:".$this->data['url14']."\r\n"; }
if ($this->data['url15']) { $this->card .= "URL;TYPE=Webpage:".$this->data['url15']."\r\n"; }

// Others
if ($this->data['photo']) { $this->card .= "PHOTO;ENCODING=BASE64;JPEG:".$this->data['photo']."\r\n"; }
if ($this->data['birthday']) { $this->card .= "BDAY:".$this->data['birthday']."\r\n"; }
if ($this->data['role']) { $this->card .= "ROLE:".$this->data['role']."\r\n"; }
if ($this->data['note']) { $this->card .= "NOTE:".$this->data['note']."\r\n"; }
$this->card .= "TZ:".$this->data['timezone']."\r\n";
$this->card .= "END:VCARD\r\n";
}
/**
* Download method streams the vcard to the browser client.
*
* @param 
* @return VCF file
*/  
function download() {
echo $this->getvcard();
return true;
}
/**
* Zipdownload method. Streams the vcard zipped to the browser client.
*
* @param 
* @return VCF file ZIPPED
*/  
function zipdownload() {
$this->log .= "vcard download() called<br />";
if (!$this->card) { $this->build(); }
if (!$this->filename) { $this->filename = trim($this->data['display_name']); }
$this->filename = str_replace(" ", "_", $this->filename);
$datavcard = $this->getvcard();
$name = $this->filename.".vcf";
$this->ci->zip->add_data($name, $datavcard);
// Write the zip file to a folder on your server.
$this->ci->zip->archive('./vcards/'.$this->filename.'.zip');
// Download the file to your desktop.
$this->ci->zip->download($this->filename.'.zip');
return true;
}
/**
* Get Vcard for Download.
*
* @param 
* @return VCF file
*/  
function getvcard() {
$this->log .= "vcard download() called<br />";
if (!$this->card) { $this->build(); }
if (!$this->filename) { $this->filename = trim($this->data['display_name']); }
$this->filename = str_replace(" ", "_", $this->filename);
header("Content-type: text/directory");
header("Content-Disposition: attachment; filename=".$this->filename.".vcf");
header("Pragma: public");
return $this->card;
//return true;
}
/**
* Get Vcard for Download.
*
* @param 
* @return VCF file
*/  
function returnvcard() {
$this->log .= "vcard download() called<br />";
if (!$this->card) { $this->build(); }
if (!$this->filename) { $this->filename = trim($this->data['display_name']); }
$this->filename = str_replace(" ", "_", $this->filename);
return $this->card;
//return true;
}
/**
* Zip Download method. Streams the vcard ARRAY zipped to the browser client.
*
* @param 
* @return VCF file ZIPPED
*/  
function zipdownloads($filename = false, $vcards = false) {
foreach($vcards as $item => $value)
{
foreach($value as $key => $val)
{
$this->ci->zip->add_data($key, $val);
}
}
// Write the zip file to a folder on your server.
$this->ci->zip->archive('./vcards/'.$filename.'.zip');
// Download the file to your desktop.
$this->ci->zip->download($filename.'.zip');
return true;
}
// ############################################################
function buildmulti($data) {
    $this->log .= "vcard build() called<br />";
    /*
    For many of the values, if they are not passed in, we set defaults or
    build them based on other values.
    */
    if (!$this->class) { $this->class = "PUBLIC"; }
    if (!$data['display_name']) {
    $data['display_name'] = trim($data['first_name']." ".$data['last_name']);
    }
    if (!$data['sort_string']) { $data['sort_string'] = $data['last_name']; }
    if (!$data['sort_string']) { $data['sort_string'] = $data['company']; }
    if (!$data['timezone']) { $data['timezone'] = date("O"); }
    if (!$this->revision_date) { $this->revision_date = date('Y-m-d H:i:s'); }
    $this->card = "BEGIN:VCARD\r\n";
    $this->card .= "VERSION:3.0\r\n";
    $this->card .= "CLASS:".$this->class."\r\n";
    $this->card .= "PRODID:-//Vcard Extended Class from bizinfosystems@gmail.com//NONSGML Version 1//EN\r\n";
    $this->card .= "REV:".$this->revision_date."\r\n";
    $this->card .= "FN:".$data['display_name']."\r\n";
    $this->card .= "N:"
    .$data['last_name'].";"
    .$data['first_name'].";"
    .$data['additional_name'].";"
    .$data['name_prefix'].";"
    .$data['name_suffix']."\r\n";
    if ($data['nickname']) { $this->card .= "NICKNAME:".$data['nickname']."\r\n"; }
    if ($data['title']) { $this->card .= "TITLE:".$data['title']."\r\n"; }
    if ($data['company']) { $this->card .= "ORG:".$data['company']; }
    if ($data['department']) { $this->card .= ";".$data['department']; }
    $this->card .= "\r\n";
    if ($data['work_po_box']
    || $data['work_extended_address']
    || $data['work_address']
    || $data['work_city']
    || $data['work_state']
    || $data['work_postal_code']
    || $data['work_country']) {
    $this->card .= "ADR;TYPE=work:"
    .$data['work_po_box'].";"
    .$data['work_extended_address'].";"
    .$data['work_address'].";"
    .$data['work_city'].";"
    .$data['work_state'].";"
    .$data['work_postal_code'].";"
    .$data['work_country']."\r\n";
    }
    if ($data['home_po_box']
    || $data['home_extended_address']
    || $data['home_address']
    || $data['home_city']
    || $data['home_state']
    || $data['home_postal_code']
    || $data['home_country']) {
    $this->card .= "ADR;TYPE=home:"
    .$data['home_po_box'].";"
    .$data['home_extended_address'].";"
    .$data['home_address'].";"
    .$data['home_city'].";"
    .$data['home_state'].";"
    .$data['home_postal_code'].";"
    .$data['home_country']."\r\n";
    }
    if ($data['email1']) { $this->card .= "EMAIL;TYPE=Work,pref:".$data['email1']."\r\n"; }
    if ($data['email2']) { $this->card .= "EMAIL;TYPE=Office Email:".$data['email2']."\r\n"; }
    if ($data['email3']) { $this->card .= "EMAIL;TYPE=Personnel Email:".$data['email3']."\r\n"; }
    if ($data['cell_tel']) { $this->card .= "TEL;TYPE=Work,voice:".$data['cell_tel']."\r\n"; }
    if ($data['office_tel']) { $this->card .= "TEL;TYPE=Work,voice:".$data['office_tel']."\r\n"; }
    if ($data['home_tel']) { $this->card .= "TEL;TYPE=Home,voice:".$data['home_tel']."\r\n"; }
    if ($data['mobile']) { $this->card .= "TEL;TYPE=X-Mobile,voice:".$data['mobile']."\r\n"; }
    if ($data['mobile_2']) { $this->card .= "TEL;TYPE=X-Mobile,voice:".$data['mobile_2']."\r\n"; }
    if ($data['fax_tel']) { $this->card .= "TEL;TYPE=work,fax:".$data['fax_tel']."\r\n"; }
    if ($data['pager_tel']) { $this->card .= "TEL;TYPE=work,pager:".$data['pager_tel']."\r\n"; }
    // URL
    if ($data['url']) { $this->card .= "URL;TYPE=Webpage:".$data['url']."\r\n"; }
    if ($data['url1']) { $this->card .= "URL;TYPE=Listing:".$data['url1']."\r\n"; }
    if ($data['url2']) { $this->card .= "URL;TYPE=Website:".$data['url2']."\r\n"; }
    if ($data['url3']) { $this->card .= "URL;TYPE=Appointment:".$data['url3']."\r\n"; }
    if ($data['url4']) { $this->card .= "URL;TYPE=Googlemap:".$data['url4']."\r\n"; }
    if ($data['url5']) { $this->card .= "URL;TYPE=Facebook:".$data['url5']."\r\n"; }
    if ($data['url6']) { $this->card .= "URL;TYPE=Twitter:".$data['url6']."\r\n"; }
    if ($data['url7']) { $this->card .= "URL;TYPE=Linkedin:".$data['url7']."\r\n"; }
    if ($data['url8']) { $this->card .= "URL;TYPE=Googleplus:".$data['url8']."\r\n"; }
    if ($data['url9']) { $this->card .= "URL;TYPE=Instagram:".$data['url9']."\r\n"; }


    if ($this->data['url10']) { $this->card .= "URL;TYPE=Web Visiting Card:".$this->data['url10']."\r\n"; }
    if ($this->data['url11']) { $this->card .= "URL;TYPE=Webpage:".$this->data['url11']."\r\n"; }
    if ($this->data['url12']) { $this->card .= "URL;TYPE=Webpage:".$this->data['url12']."\r\n"; }
    if ($this->data['url13']) { $this->card .= "URL;TYPE=Webpage:".$this->data['url13']."\r\n"; }
    if ($this->data['url14']) { $this->card .= "URL;TYPE=Webpage:".$this->data['url14']."\r\n"; }
    if ($this->data['url15']) { $this->card .= "URL;TYPE=Webpage:".$this->data['url15']."\r\n"; }
    //if ($data['photo']) { $this->card .= "PHOTO;ENCODING=BASE64;JPEG:".$data['photo']."\r\n"; }
    //if ($data['photo']) { $this->card .= "PHOTO;ENCODING=BASE64;JPEG:".$data['photo']."\r\n"; }
    if ($data['birthday']) { $this->card .= "BDAY:".$data['birthday']."\r\n"; }
    if ($data['role']) { $this->card .= "ROLE:".$data['role']."\r\n"; }
    if ($data['note']) { $this->card .= "NOTE:".$data['note']."\r\n"; }
    $this->card .= "TZ:".$data['timezone']."\r\n";
    $this->card .= "END:VCARD\r\n";
    }
    
    function downloadmulti($multiVcard) {
      //print_r($multiVcard);
      $count = 0;
      $this->arra_data = array();
      $this->arra_data = $this->data;       
      $this->log .= "vcard download() called<br />";
      ##
      foreach($multiVcard as $value){
      //$this->data = array();
      $this->buildmulti($value);
      echo $this->card;
      if($count == 0){
      if (!$this->filename) { $this->filename = trim($value['company']); }
      $this->filename = str_replace(" ", "_", $this->filename);
    }
    $count = $count + 1;
    }  
    //echo $this->filename;        
    ##
    ##

    header("Content-type: text/directory");
    header("Content-Disposition: attachment; filename=".$this->filename.".vcf");
    header("Pragma: public");
    return $data;
         
    //return true;*/
    /*header("Pragma: public");
    header("Expires: 0"); // set expiration time
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Content-type: text/plain');
    header("Content-Disposition: attachment; filename=".$this->filename.".vcf");
    header("Pragma: public"); */       

    ## 
    }
}