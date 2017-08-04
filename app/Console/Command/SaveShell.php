<?class SaveShell extends AppShell {
    



    public function main() {

        $this->out(get_current_user());
    	$filename = getcwd();
        $filename .= "/webroot/".date('Y', time())."/";//.date('m', time())."/";
        if(!file_exists($filename)){
            mkdir($filename, 0777, true);
            chmod($filename, 0777);
        }       
        $filename .= date('m', time())."/";
        if(!file_exists($filename)){
            mkdir($filename, 0777, true);
            chmod($filename, 0777);
        }
        $filename .= date('m_d_Y_h_i_s_a', time()).".zip";
    	$download = "http://playok.com/tmp/reversi-games.zip";

    	//$download = "https://www.google.de/images/google_favicon_128.png";

    	$file = file_get_contents($download);
    	if(	file_put_contents($filename, $file)){
    		$this->out($filename);
    	}else{
    		$this->out("ERROR");
    	}

    }
}?>