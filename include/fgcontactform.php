<?PHP
/*
    Contact Form from HTML Form Guide

    This program is free software published under the
    terms of the GNU Lesser General Public License.

This program is distributed in the hope that it will
be useful - WITHOUT ANY WARRANTY; without even the
implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.

@copyright html-form-guide.com 2010
*/
//require_once("class.phpmailer.php");
require 'PHPMailer/PHPMailerAutoload.php';

/*
Interface to Captcha handler
*/
class FG_CaptchaHandler
{
    function Validate() { return false;}
    function GetError(){ return '';}
}
/*
FGContactForm is a general purpose contact form class
It supports Captcha, HTML Emails, sending emails
conditionally, File atachments and more.
*/
class FGContactForm
{
    var $receipients;
    var $errors;
    var $error_message;
    var $name;
    var $email;
    var $message;
    var $from_address;
    var $form_random_key;
    var $conditional_field;
    var $arr_conditional_receipients;
    var $fileupload_fields;
    var $captcha_handler;

    var $mailer;

    function FGContactForm()
    {
        $this->receipients = array();
        $this->errors = array();
        $this->form_random_key = 'HTgsjhartag';
        $this->conditional_field='';
        $this->arr_conditional_receipients=array();
        $this->fileupload_fields=array();

        $this->mailer = new PHPMailer();
        $this->mailer->CharSet = 'utf-8';
	
	$this->mailer->IsSMTP();
	//$this->mailer->SMTPDebug  =2; 
	$this->mailer->SMTPAuth   = true;
	$this->mailer->Host       = "mail.magicmail.ninja";
	$this->mailer->Port       = 25;
	$this->mailer->Username   = "noreply@yunified.com";
	$this->mailer->Password   = "bqGykfNWZx";     
	$this->mailer->SMTPOptions = array(
	    'ssl' => array(
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => true
	    )
	);   
	session_start();
    }

    function EnableCaptcha($captcha_handler)
    {
        $this->captcha_handler = $captcha_handler;
        session_start();
    }

    function AddRecipient($email,$name="")
    {
        $this->mailer->AddAddress($email,$name);
    }

    function SetFromAddress($from)
    {
        $this->from_address = $from;
    }
    function SetFormRandomKey($key)
    {
        $this->form_random_key = $key;
    }
    function GetSpamTrapInputName()
    {
        return 'sp'.md5('KHGdnbvsgst'.$this->GetKey());
    }
    function SafeDisplay($value_name)
    {
        if(empty($_POST[$value_name]))
        {
            return'';
        }
        return htmlentities($_POST[$value_name]);
    }
    function GetFormIDInputName()
    {
        $rand = md5('TygshRt'.$this->GetKey());

        $rand = substr($rand,0,20);
        return 'id'.$rand;
    }


    function GetFormIDInputValue()
    {
        return md5('jhgahTsajhg'.$this->GetKey());
    }

    function SetConditionalField($field)
    {
        $this->conditional_field = $field;
    }
    function AddConditionalReceipent($value,$email)
    {
        $this->arr_conditional_receipients[$value] =  $email;
    }

    function AddFileUploadField($file_field_name,$accepted_types,$max_size)
    {

        $this->fileupload_fields[] =
            array("name"=>$file_field_name,
            "file_types"=>$accepted_types,
            "maxsize"=>$max_size);
    }

    function ProcessForm()
    {
        if(!isset($_POST['submitted']))
        {
           return false;
        }
        if(!$this->Validate())
        {
            $this->error_message = implode('<br/>',$this->errors);
            return false;
        }
        $this->CollectData();

        $ret = $this->SendFormSubmission();

        return $ret;
    }

    function RedirectToURL($url)
    {
        header("Location: $url");
        exit;
    }

    function GetErrorMessage()
    {
        return $this->error_message;
    }
    function GetSelfScript()
    {
        return htmlentities($_SERVER['PHP_SELF']);
    }

    function GetName()
    {
        return $this->name;
    }
    function GetEmail()
    {
        return $this->email;
    }
    function GetMessage()
    {
        return htmlentities($this->message,ENT_QUOTES,"UTF-8");
    }

/*--------  Private (Internal) Functions -------- */


    function SendFormSubmission()
    {
        $this->CollectConditionalReceipients();

        $this->mailer->CharSet = 'utf-8';
        
        $this->mailer->Subject = "Contact form submission from $this->name";

       $this->mailer->From = $this->GetFromAddress();


        $this->mailer->FromName = $this->name;

        $this->mailer->AddReplyTo($this->email);

	$this->mailer->IsHTML(true);  		

        $body             = file_get_contents('include/email-html/contact_email.html');

        $body = str_replace(array('{user_name}','{user_email}','{company_name}','{department}','{message}'), $this->getDataForm(), $body);

       	$this->mailer->MsgHTML($body);

	/*$content = $this->FormSubmissionToMail();
        $message = $this->ComposeFormtoEmail();
	
        $textMsg = trim(strip_tags(preg_replace('/<(head|title|style|script)[^>]*>.*?<\/\\1>/s','',$message)));
        $this->mailer->AltBody = @html_entity_decode($textMsg,ENT_QUOTES,"UTF-8");
        $this->mailer->MsgHTML($message);*/

        $this->AttachFiles();
        if(!$this->mailer->Send())
        {
            $this->add_error("Failed sending email!");
            return false;
        }

        return true;
    }
function getDataForm(){
	$userInfo = array();	

	$userName = isset($_POST['user_name'])?$_POST['user_name']:'';
	$userEmail = isset($_POST['user_email'])?$_POST['user_email']:'';
	$companyName = isset($_POST['company_name'])?$_POST['company_name']:'';
	$department = isset($_POST['department'])?$_POST['department']:'';
	$message = isset($_POST['message'])?$_POST['message']:'';

	$userInfo = array('user_name'=>$userName,'user_email'=>$userEmail,'company_name'=>$companyName,'department'=>$department,'message'=>$message);
	//$userInfo = array($userName,$userEmail,$companyName,$department,$message);
	
	return $userInfo;
}
    function CollectConditionalReceipients()
    {
        if(count($this->arr_conditional_receipients)>0 &&
          !empty($this->conditional_field) &&
          !empty($_POST[$this->conditional_field]))
        {
            foreach($this->arr_conditional_receipients as $condn => $rec)
            {
                if(strcasecmp($condn,$_POST[$this->conditional_field])==0 &&
                !empty($rec))
                {
                    $this->AddRecipient($rec);
                }
            }
        }
    }

    /*
    Internal variables, that you donot want to appear in the email
    Add those variables in this array.
    */
    function IsInternalVariable($varname)
    {
        $arr_interanl_vars = array('scaptcha',
                            'submitted',
                            $this->GetSpamTrapInputName(),
                            $this->GetFormIDInputName()
                            );
        if(in_array($varname,$arr_interanl_vars))
        {
            return true;
        }
        return false;
    }

    function FormSubmissionToMail()
    {
        $ret_str='';
        foreach($_POST as $key=>$value)
        {
            if(!$this->IsInternalVariable($key))
            {
                $value = htmlentities($value,ENT_QUOTES,"UTF-8");
                $value = nl2br($value);
                $key = ucfirst($key);
                $ret_str .= "<div class='label'>$key :</div><div class='value'>$value </div>\n";
            }
        }
        foreach($this->fileupload_fields as $upload_field)
        {
            $field_name = $upload_field["name"];
            if(!$this->IsFileUploaded($field_name))
            {
                continue;
            }        
            
            $filename = basename($_FILES[$field_name]['name']);

            $ret_str .= "<div class='label'>File upload '$field_name' :</div><div class='value'>$filename </div>\n";
        }
        return $ret_str;
    }

    function ExtraInfoToMail()
    {
        $ret_str='';

        $ip = $_SERVER['REMOTE_ADDR'];
        $ret_str = "<div class='label'>IP address of the submitter:</div><div class='value'>$ip</div>\n";

        return $ret_str;
    }

    function GetMailStyle()
    {
        $retstr = "\n<style>".
        "body,.label,.value { font-family:Arial,Verdana; } ".
        ".label {font-weight:bold; margin-top:5px; font-size:1em; color:#333;} ".
        ".value {margin-bottom:15px;font-size:0.8em;padding-left:5px;} ".
        "</style>\n";

        return $retstr;
    }
    function GetHTMLHeaderPart()
    {
         $retstr = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'."\n".
                   '<html><head><title></title>'.
                   '<meta http-equiv=Content-Type content="text/html; charset=utf-8">';
         $retstr .= $this->GetMailStyle();
         $retstr .= '</head><body>';
         return $retstr;
    }
    function GetHTMLFooterPart()
    {
        $retstr ='</body></html>';
        return $retstr ;
    }
    function ComposeFormtoEmail()
    {
        $header = $this->GetHTMLHeaderPart();
        $formsubmission = $this->FormSubmissionToMail();
        $extra_info = $this->ExtraInfoToMail();
        $footer = $this->GetHTMLFooterPart();

        $message = $header."Submission from 'contact us' form:<p>$formsubmission</p><hr/>$extra_info".$footer;

        return $message;
    }

    function AttachFiles()
    {
        foreach($this->fileupload_fields as $upld_field)
        {
            $field_name = $upld_field["name"];
            if(!$this->IsFileUploaded($field_name))
            {
                continue;
            }
            
            $filename =basename($_FILES[$field_name]['name']);

            $this->mailer->AddAttachment($_FILES[$field_name]["tmp_name"],$filename);
        }
    }

    function GetFromAddress()
    {
        if(!empty($this->from_address))
        {
            return $this->from_address;
        }

        $host = $_SERVER['SERVER_NAME'];

        $from ="nobody@$host";
        return $from;
    }

    function Validate()
    {
        $ret = true;
        //security validations
        if(empty($_POST[$this->GetFormIDInputName()]) ||
          $_POST[$this->GetFormIDInputName()] != $this->GetFormIDInputValue() )
        {
            //The proper error is not given intentionally
            $this->add_error("Automated submission prevention.");
            $ret = false;
        }

        //This is a hidden input field. Humans won't fill this field.
        if(!empty($_POST[$this->GetSpamTrapInputName()]) )
        {
            //The proper error is not given intentionally
            $this->add_error("Automated submission prevention!.");
            $ret = false;
        }

        //name validations
        if(empty($_POST['user_name']))
        {
            $this->add_error("Please provide your name");
            $ret = false;
        }
        else
        if(strlen($_POST['user_name'])>50)
        {
            $this->add_error("Name is too big!");
            $ret = false;
        }

        //email validations
        if(empty($_POST['user_email']))
        {
            $this->add_error("Please provide your email address");
            $ret = false;
        }
        else
        if(strlen($_POST['user_email'])>50)
        {
            $this->add_error("Email address is too big!");
            $ret = false;
        }
        else
        if(!$this->validate_email($_POST['user_email']))
        {
            $this->add_error("Please provide a valid email address");
            $ret = false;
        }
	//company validations
        if(empty($_POST['company_name']))
        {
            $this->add_error("Please provide your name");
            $ret = false;
        }
        else
        if(strlen($_POST['company_name'])>50)
        {
            $this->add_error("Company is too big!");
            $ret = false;
        }
        //message validaions
        if(strlen($_POST['message'])>2048)
        {
            $this->add_error("Message is too big!");
            $ret = false;
        }
        
        //captcha validaions
	if(isset($_POST['captchaTxt']))
        {
            $captchaTxt = intval($_POST['captchaTxt']);
            if(intval($_SESSION["captchaVal"]) !== intval($_POST['captchaTxt'])){
                $this->add_error("Captcha is not valid!");
                $ret = false;
            }
        }else{
            $this->add_error("Captcha is required!");
            $ret = false;
	}
	
        /*if(isset($this->captcha_handler))
        {
            if(!$this->captcha_handler->Validate())
            {
                $this->add_error($this->captcha_handler->GetError());
                $ret = false;
            }
        }*/
        //file upload validations
        if(!empty($this->fileupload_fields))
        {
         if(!$this->ValidateFileUploads())
         {
            $ret = false;
         }
        }
        return $ret;
    }

    function ValidateFileType($field_name,$valid_filetypes)
    {
        $ret=true;
        $info = pathinfo($_FILES[$field_name]['name']);
        $extn = $info['extension'];
        $extn = strtolower($extn);

        $arr_valid_filetypes= explode(',',$valid_filetypes);
        if(!in_array($extn,$arr_valid_filetypes))
        {
            $this->add_error("Valid file types are: $valid_filetypes");
            $ret=false;
        }
        return $ret;
    }

    function ValidateFileSize($field_name,$max_size)
    {
        $size_of_uploaded_file =
                $_FILES[$field_name]["size"]/1024;//size in KBs
        if($size_of_uploaded_file > $max_size)
        {
            $this->add_error("The file is too big. File size should be less than $max_size KB");
            return false;
        }
        return true;
    }

    function IsFileUploaded($field_name)
    {
        if(empty($_FILES[$field_name]['name']))
        {
            return false;
        }
        if(!is_uploaded_file($_FILES[$field_name]['tmp_name']))
        {
            return false;
        }
        return true;
    }
    function ValidateFileUploads()
    {
        $ret=true;
        foreach($this->fileupload_fields as $upld_field)
        {
            $field_name = $upld_field["name"];

            $valid_filetypes = $upld_field["file_types"];
            
            if(!$this->IsFileUploaded($field_name))
            {
                continue;
            }

            if($_FILES[$field_name]["error"] != 0)
            {
                $this->add_error("Error in file upload; Error code:".$_FILES[$field_name]["error"]);
                $ret=false;
            }

            if(!empty($valid_filetypes) &&
             !$this->ValidateFileType($field_name,$valid_filetypes))
            {
                $ret=false;
            }

            if(!empty($upld_field["maxsize"]) &&
            $upld_field["maxsize"]>0)
            {
                if(!$this->ValidateFileSize($field_name,$upld_field["maxsize"]))
                {
                    $ret=false;
                }
            }

        }
        return $ret;
    }

    function StripSlashes($str)
    {
        if(get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }
        return $str;
    }
    /*
    Sanitize() function removes any potential threat from the
    data submitted. Prevents email injections or any other hacker attempts.
    if $remove_nl is true, newline chracters are removed from the input.
    */
    function Sanitize($str,$remove_nl=true)
    {
        $str = $this->StripSlashes($str);

        if($remove_nl)
        {
            $injections = array('/(\n+)/i',
                '/(\r+)/i',
                '/(\t+)/i',
                '/(%0A+)/i',
                '/(%0D+)/i',
                '/(%08+)/i',
                '/(%09+)/i'
                );
            $str = preg_replace($injections,'',$str);
        }

        return $str;
    }

    /*Collects clean data from the $_POST array and keeps in internal variables.*/
    function CollectData()
    {
        $this->name = $this->Sanitize($_POST['user_name']);
        $this->email = $this->Sanitize($_POST['user_email']);

        /*newline is OK in the message.*/
        $this->message = $this->StripSlashes($_POST['message']);
    }

    function add_error($error)
    {
        array_push($this->errors,$error);
    }
    function validate_email($email)
    {
        return preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/", $email);
    }

    function GetKey()
    {
        return $this->form_random_key.$_SERVER['SERVER_NAME'].$_SERVER['REMOTE_ADDR'];
    }
	 /* This function will use the integer value of $operand to show either a plus, minus, or times. */
    public function getCaptcha() {	
        
        $num1 = rand(0,10); // pick a random number from 0 to 10 inclusive
        $num2 = rand(0,10); // same idea
        $num3 = rand(0,2); // 0 = plus, 1 = minus, 2 = multiply
        switch($num3) {
            case 0: 
		$total = $num1+$num2;
            	$_SESSION["captchaVal"] = $total;
                return $num1." + ".$num2; 
                break;
            case 1: 
                $res = ($num1<$num2)? $num2 - $num1: $num1-$num2;
                
            	$_SESSION["captchaVal"] = $res;
                return ($num1<$num2)?$num2." - ".$num1:$num1." - ".$num2; 
                break;
            case 2: 
		$res = $num1*$num2;
            	$_SESSION["captchaVal"] = $res;               
                return $num1." * ".$num2; 
                break;
            default: 
                $total = $num1+$num2;
            	$_SESSION["captchaVal"] = $total;
                break; //Remark: We shouldn't ever get down here.
         }
    }

}

?>
