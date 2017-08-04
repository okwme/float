<?
echo $this->Form->create("Photo", array("type"=>"file", "controller"=>"photos", "action"=>"upload"));
echo $this->Form->file("file");
echo $this->Form->end("submit");

?>
