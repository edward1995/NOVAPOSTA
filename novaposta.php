<?php
class NP {
    // Ключ АПИ
    protected $apikey='';
    //Язык получения справочников
    protected $language='ru';
    // Имя отправителя
    protected $fnameSender='';
    // Фамилия Отправителя
    protected $lnameSender='';
    //Ref города отправителя
    protected $RefCitySender='';
    //Телефон Отправителя
    protected $PhoneSender='';
    //Ref отделения Отправителя
    protected $RefWarenhouse='';
    

       function send($xml){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://api.novaposhta.ua/v2.0/json/');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/json"));
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($xml));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		return json_decode($response);
    }
    
       function city() {
        $arr = array(
        'apiKey'=>$this->apikey,
        'modelName'=>'Address',
        'calledMethod'=>'getCities',
        'methodProperties'=>array('Page'=>'1')
        );
		return $this->send($arr);
    }
  function warenhouse($filter=false){ 
         $arr = array(
        'apiKey'=>$this->apikey,
        'modelName'=>'Address',
        'calledMethod'=>'getWarehouses',
        'methodProperties'=>array('CityRef'=>$filter)
        );
        return $this->send($arr);
  
	}
    
         function contact($lname,$fname,$phone,$city,$middle='',$email=''){
                $arr = array(   
                'apiKey'=>$this->apikey,
                'modelName'=>'Counterparty',
                'calledMethod'=>'save',
                'methodProperties'=> array(
                'CityRef'=>$city,
                'FirstName'=>$fname,
                'MiddleName'=>$middle,
                'LastName'=>$lname,
                'Phone'=>$phone,
                'Email'=>$email,
                'CounterpartyType'=>'PrivatePerson',
                'CounterpartyProperty'=>'Recipient'
                ),
                );
                return $this->send($arr);
                }   
                
                
                function getPayType(){
                    $arr = array(
                    'apiKey'=>$this->apikey,
                    'modelName'=>'Common',
                    'calledMethod'=>'getTypesOfPayersForRedelivery',
                    'methodProperties'=>array(''=>''),
                    'language'=>$this->language
                    );
        return $this->send($arr);
        }                    
                
                function printttn($ref){
                    
                    $arr = array(
                    'apiKey'=>$this->apikey,
                    'modelName'=>'InternetDocument',
                    'calledMethod'=>'printDocument',
                    'methodProperties'=>array(
                    'DocumentRefs'=>array('$ref'),
                    'Type'=>'Pdf'
                    ),
                    );
                    
                    
                    return $this->send($arr);
                }    
                 function getPaymentForms(){
                     $arr = array(
                    'apiKey'=>$this->apikey,
                    'modelName'=>'Common',
                    'calledMethod'=>'getPaymentForms',
                    'methodProperties'=>array(''=>''),
                    'language'=>$this->language
                    );
                          return $this->send($arr);
                }    
                
               function getServiceTypes() {
                
                   $arr = array(
                    'apiKey'=>$this->apikey,
                    'modelName'=>'Common',
                    'calledMethod'=>'getServiceTypes',
                    'methodProperties'=>array(''=>''),
                    'language'=>$this->language
                    );
                
                return $this->send($arr);
               }
               
               function getTiresWheelsList(){
                  $arr = array(
                    'apiKey'=>$this->apikey,
                    'modelName'=>'Common',
                    'calledMethod'=>'getTiresWheelsList',
                    'methodProperties'=>array(''=>''),
                    'language'=>$this->language
                    );
                return $this->send($arr);
               }
               function getTraysList() {
               $arr = array(
                    'apiKey'=>$this->apikey,
                    'modelName'=>'Common',
                    'calledMethod'=>'getTraysList',
                    'methodProperties'=>array(''=>''),
                    'language'=>$this->language
                    );
                
                return $this->send($arr);
               }
               
               function getTypesOfCounterparties(){
                $arr = array(
                    'apiKey'=>$this->apikey,
                    'modelName'=>'Common',
                    'calledMethod'=>'getTypesOfCounterparties',
                    'methodProperties'=>array(''=>''),
                    'language'=>$this->language
                    );
                return $this->send($arr);
               }
               
               
               function getTypesOfPayers(){
                $arr = array(
                    'apiKey'=>$this->apikey,
                    'modelName'=>'Common',
                    'calledMethod'=>'getTypesOfPayers',
                    'methodProperties'=>array(''=>''),
                    'language'=>$this->language
                    );
                return $this->send($arr);
               }
               
               function getTypesOfPayersForRedelivery(){
                $arr = array(
                    'apiKey'=>$this->apikey,
                    'modelName'=>'Common',
                    'calledMethod'=>'getTypesOfPayersForRedelivery',
                    'methodProperties'=>array(''=>''),
                    'language'=>$this->language
                    );
                return $this->send($arr);
               }
                
                
                    
                
                
                
function ttn($order_id, $city, $warehouse, $lname, $fname, $phone, $weight, $pub_price, $payer) {
        
        $send = $this->contact($this->lnameSender,$this->fnameSender,$this->PhoneSender,$this->RefCitySender);
        $con =$this->contact($lname,$fname,$phone,$city);
        $arr = array(
        'apiKey'=>$this->apikey,
        'modelName'=>'InternetDocument',
        'calledMethod'=>'save',
        'methodProperties'=>array(
        'PayerType'=>$payer,
        'PaymentMethod'=>'Cash',
        'DateTime'=>date("d.m.Y"),
        'Weight'=>$weight,
        'VolumeGeneral'=>$weight/1000,
        'CargoType'=>'Cargo',
        'ServiceType'=>'WarehouseWarehouse',
        'SeatsAmount'=>'1',
        'Description'=>'Комп/комплектующие',
        'Cost'=>$pub_price,
        'CitySender'=>$this->RefCitySender,
        'Sender'=>$send->data[0]->Ref,
        'SenderAddress'=>$this->RefCitySender,
        'ContactSender'=>$send->data[0]->ContactPerson->data[0]->Ref,
        'SendersPhone'=>$this->PhoneSender,
        'CityRecipient'=>$city,
        'Recipient'=>$con->data[0]->Ref,
        'RecipientAddress'=>$warehouse,
        'ContactRecipient'=>$con->data[0]->ContactPerson->data[0]->Ref,
        'RecipientsPhone'=>$phone,
        'AdditionalInformation'=>$order_id
        )
        );
        return $this->send($arr);
    }
                
                
                
                  
}