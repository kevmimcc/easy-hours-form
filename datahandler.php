<pre>
<?php

class Hours{
    public $day_data;
    public $days;
    public $day_codes;
    public function __construct($day_data){
        $this->day_data = $day_data;
        $this->days = array("monday"=>0, "tuesday"=>1, "wednesday"=>2, "thursday"=>3, "friday"=>4, "saturday"=>5, "sunday"=>6);
        $this->day_codes = array("monday"=>"Mo", "tuesday"=>"Tu", "wednesday"=>"We", "thursday"=>"Th", "friday"=>"Fr", "saturday"=>"Sa", "sunday"=>"Su");
    }
    public function meta_hours(){
        $i=0;
        foreach($this->day_data as $key=>$value){
            if($value['status']){
                $timespan=$value['open']['hour'].$value['open']['minute'].$value['close']['hour'].$value['close']['minute'];
                $groups[$timespan][]=$this->day_data[$key];
            }
            $i++;
        }
        return $groups;
    }
}
$days['monday'] = $_POST['monday'];
$days['tuesday'] = $_POST['tuesday'];
$days['wednesday'] = $_POST['wednesday'];
$days['thursday'] = $_POST['thursday'];
$days['friday'] = $_POST['friday'];
$days['saturday'] = $_POST['saturday'];
$days['sunday'] = $_POST['sunday'];
//foreach($days as $key=>$value){
//    if($value['status']){
//        $times[opens][]=$value['open']['hour'].':'.$value['open']['minute'];
//        $times[closes][]=$value['close']['hour'].':'.$value['close']['minute'];
//    }//
//}
$hours = new Hours($days);
print_r($hours->meta_hours());
?>
</pre>
