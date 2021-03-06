<?PHP

require_once("./include/fgcontactform.php");

$formproc = new FGContactForm();

$formproc->SetFromAddress('noreply@yunified.com');
$formproc->AddRecipient('contact@yunified.com');//email to receive

$formproc->SetFormRandomKey('CnRrspl1FyEylUj');


if(isset($_POST['submitted']))
{

   if($formproc->ProcessForm())
   {
        unset($_SESSION["captchaVal"]);
        echo '<script>alert("Email has been sent!"); window.location = "http://yunified.com";</script>';
        //$formproc->RedirectToURL("http://www.yunified.com?send=1");
   }
}

?>
<!doctype html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=0"/>
        <meta charset="utf-8">
        <title>Contact | [yunified]</title>
        <script src="js/jquery.js"></script>
        <script src="js/mini_slider.js"></script>
	<script type='text/javascript' src='js/h5validate.js'></script>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/css.css">
        <link href="favicon.ico" type="image/x-icon" rel="icon">
        <link href="favicon.ico" type="image/x-icon" rel="shortcut icon">
    </head>

    <body>
        <div id="page-bg">
            <div id="logo"><a href="/"><img src="img/logo.png" width="270" height="69" alt=""/></a></div>
            <div id="vertical">
                <div id="title" class="contact-title">
                    <h2>Contact us</h2>
                    <h3>We eager to please you with individual solutions</h3>
                </div>
                <div id="content">
		<div><span class='error'><?php echo $formproc->GetErrorMessage(); ?></span></div>
                    <form id="contact-form" class="myForm" action="<?php echo $formproc->GetSelfScript(); ?>" method='post' accept-charset='UTF-8'>
			<input type='hidden' name='submitted' id='submitted' value='1'/>
			<input type='hidden' name='<?php echo $formproc->GetFormIDInputName(); ?>' value='<?php echo $formproc->GetFormIDInputValue(); ?>'/>
			<input type='hidden'  class='spmhidip' name='<?php echo $formproc->GetSpamTrapInputName(); ?>' />

                        <div class="col-wrap">
                            <div class="col-50"><input type="text" name="user_name" maxlength="255" id="user_name" value="<?php echo $formproc->SafeDisplay('user_name') ?>" required placeholder="Your name"/></div>
                            <div class="col-50"><input type="email" name="user_email" maxlength="50" class="h5-email" id="user_email" value="<?php echo $formproc->SafeDisplay('user_email') ?>" required placeholder="Your email address"/></div>
                        </div>
                        <div class="col-wrap">
                            <div class="col-50"><input type="text" name="company_name" maxlength="255" id="company_name" value="<?php echo $formproc->SafeDisplay('company_name') ?>" required placeholder="Your company"/></div>
                            <div class="col-50"><input type="text" name="department" maxlength="50" id="department" value="<?php echo $formproc->SafeDisplay('department') ?>" placeholder="Department"/></div>
                        </div>
                        <div class="col-wrap">
                            <div class="col-100"><textarea name="message" id="message" maxlength="255"  cols="30" rows="10" required placeholder="Your message*"><?php echo $formproc->SafeDisplay('message') ?></textarea></div>
                        </div>
                        <div class="col-wrap captcha-wrap">
                            <span class="captcha">What’s the total of <span><?php echo $formproc->getCaptcha();?> ?</span></span> <input class="captchaTxt"  name="captchaTxt" type="text" required/>
                        </div>
                        <div class="col-wrap">
                            <div class="col-100"><button class="myButton">Submit</button></div>
                        </div>
<script type='text/javascript'>
$('#black').h5Validate({
	errorClass:'form-error'
});
</script>
                    </form>
                </div>
            </div>
            <div id="privacypolicy"  class="overlay">
                <a id="close-area"  href=""></a>
                <div class="popup">
                    <a class="close" href="">×</a>
                    <div class="tos-logo-popup">
                        <a href="/" id="logo"><img src="img/logo_1.png" alt="yieldbids"></a>	
                    </div>	

                    <div id="tos-content">
                        <h3 class="privacy">Privacy Policy</h3>
                        <p>Your privacy is critically important to us. At yunified (or “MagicGroup Asia Pte. Ltd.”, “MagicGroup”) we have a few fundamental principles:</p>

                        <ul>
                            <li>
                                We don’t ask you for personal information unless we truly need it. (We can’t stand services that ask you for things like your gender or income level for no apparent reason.)
                            </li>
                            <li>
                                We don’t share your personal information with anyone except to comply with the law, develop our products, or protect our rights.
                            </li>
                            <li>
                                We don’t store personal information on our servers unless required for the on-going operation of one of our services.
                            </li>
                        </ul>

                        <p>Below is our privacy policy which incorporates these goals</p>
                        <p>If you have questions about deleting or correcting your personal data please <a href="mailto:legal@yunified.com" target="_top">contact us</a>.</p>


                        <p>
                            MagicGroup Asia Pte. Ltd (“ MagicGroup”) operates several websites including <a href="http://www.yunified.com" target="_blank">http://www.yunified.com</a>, <a href="http://www.magicgroup.asia" target="_blank">http://www.magicgroup.asia</a>. It is MagicGroup’s policy to respect your privacy regarding any information we may collect while operating our websites.
                        </p>
                        <h4>Website Visitors</h4>
                        <p>
                            Like most website operators, MagicGroup collects non-personally-identifying information of the sort that web browsers and servers typically make available, such as the browser type, language preference, referring site, and the date and time of each visitor request. MagicGroup’s purpose in collecting non-personally identifying information is to better understand how MagicGroup’s visitors use its website. From time to time, MagicGroup may release non-personally-identifying information in the aggregate, e.g., by publishing a report on trends in the usage of its website.
                        </p>

                        <h4>Gathering of Personally-Identifying Information</h4> 
                        <p>
                            Certain visitors to MagicGroup’s websites choose to interact with MagicGroup in ways that require MagicGroup to gather personally-identifying information. The amount and type of information that MagicGroup gathers depends on the nature of the interaction. Those who engage in transactions with MagicGroup are asked to provide additional information, including as necessary the personal and financial information required to process those transactions. In each case, MagicGroup collects such information only insofar as is necessary or appropriate to fulfill the purpose of the visitor’s interaction with MagicGroup. MagicGroup does not disclose personally-identifying information other than as described below. And visitors can always refuse to supply personally-identifying information, with the caveat that it may prevent them from engaging in certain website-related activities.
                        </p>

                        <h4>Aggregated Statistics</h4> 
                        <p>
                            MagicGroup may collect statistics about the behavior of visitors to its websites. MagicGroup may display this information publicly or provide it to others. However, MagicGroup does not disclose personally-identifying information other than as described below.
                        </p>
                        <h4> 
                            Protection of Certain Personally-Identifying Information
                        </h4>
                        <p>
                            MagicGroup discloses potentially personally-identifying and personally-identifying information only to those of its employees, contractors and affiliated organizations that (i) need to know that information in order to process it on MagicGroup’s behalf or to provide services available at MagicGroup’s websites, and (ii) that have agreed not to disclose it to others. Some of those employees, contractors and affiliated organizations may be located outside of your home country; by using MagicGroup’s websites, you consent to the transfer of such information to them. MagicGroup will not rent or sell potentially personally-identifying and personally-identifying information to anyone. Other than to its employees, contractors and affiliated organizations, as described above, MagicGroup discloses potentially personally-identifying and personally-identifying information only in response to a subpoena, court order or other governmental request, or when MagicGroup believes in good faith that disclosure is reasonably necessary to protect the property or rights of MagicGroup, third parties or the public at large. If you are a registered user of a MagicGroup website and have supplied your email address, MagicGroup may occasionally send you an email to tell you about new features, solicit your feedback, or just keep you up to date with what’s going on with MagicGroup and our products. We primarily use our various product blogs to communicate this type of information, so we expect to keep this type of email to a minimum. If you send us a request (for example via a support email or via one of our feedback mechanisms), we reserve the right to publish it in order to help us clarify or respond to your request or to help us support other users. MagicGroup takes all measures reasonably necessary to protect against the unauthorized access, use, alteration or destruction of potentially personally-identifying and personally-identifying information.
                        </p>

                        <h4>Cookies</h4> 
                        <p>
                            A cookie is a string of information that a website stores on a visitor’s computer, and that the visitor’s browser provides to the website each time the visitor returns. MagicGroup uses cookies to help MagicGroup identify and track visitors, their usage of MagicGroup website, and their website access preferences. MagicGroup visitors who do not wish to have cookies placed on their computers should set their browsers to refuse cookies before using MagicGroup’s websites, with the drawback that certain features of MagicGroup’s websites may not function properly without the aid of cookies.
                        </p>

                        <h4>Business Transfers</h4>
                        <p>
                            If MagicGroup, or substantially all of its assets, were acquired, or in the unlikely event that MagicGroup goes out of business or enters bankruptcy, user information would be one of the assets that is transferred or acquired by a third party. You acknowledge that such transfers may occur, and that any acquirer of MagicGroup may continue to use your personal information as set forth in this policy.
                        </p>

                        <h4>Ads</h4> 
                        <p>
                            Ads appearing on any of our websites may be delivered to users by advertising partners, who may set cookies. These cookies allow the ad server to recognize your computer each time they send you an online advertisement to compile information about you or others who use your computer. This information allows ad networks to, among other things, deliver targeted advertisements that they believe will be of most interest to you. This Privacy Policy covers the use of cookies by MagicGroup and does not cover the use of cookies by any advertisers.
                        </p>
                        <h4>Privacy Policy Changes</h4>
                        <p>
                            Although most changes are likely to be minor, MagicGroup may change its Privacy Policy from time to time, and in MagicGroup’s sole discretion. MagicGroup encourages visitors to frequently check this page for any changes to its Privacy Policy. Your continued use of this site after any change in this Privacy Policy will constitute your acceptance of such change.
                        </p>
                        <p>MagicGroup Asia Pte. Ltd., Singapore, 1st December 2015</p>
                    </div>
                </div>
            </div>
            <div id="footer">
                <a href="http://login.yunified.com/login"> Trading Desk Login</a>
                <a href="contact.php">Contact</a>
                <a class="button" href="#privacypolicy">Privacy Policy </a>
            </div>
        </div>

    </body>
</html>
