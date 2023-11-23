<?php 
include("Mail.php");
//ini_set('display_errors', 'On');

$html_header = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
$html_header .= '<html xmlns="http://www.w3.org/1999/xhtml">';
$html_header .= '<head>';
$html_header .= '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />';
$html_header .= '<title>Registration</title>';
$html_header .= '<link rel="shortcut icon" href="http://physics.aalto.fi/pub/boat/figs/aalto_icon.png" type="image/png" />';
$html_header .= '<link rel="icon" href="http://physics.aalto.fi/pub/boat/figs/aalto_icon.png" type="image/png" />';
$html_header .= '<link rel="stylesheet" href="form.css" type="text/css" />';
$html_header .= '<style type="text/css"></style>';
$html_header .= '<script type="text/x-mathjax-config">
  MathJax.Hub.Config({tex2jax: {inlineMath: [["$","$"]]}});
</script>
<script type="text/javascript"
  src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
</script>';
$html_header .= '</head>';
$html_header .= '<body>';


$html_tail = '</body></html>';


function form_info($info){
  $output =  '<tr><td class="label">';
  $output .= '</td><td class="input" align="right"><p class="info">'.$info.'</p></td></tr>';
  return $output;
}

function form_textfield($name,$form_name,$req,$fill,$size){
  $output =  '<tr><td class="label">';
  if($req == 1){
    if($fill == ""){
      $output .= '<em class="needed">'.$name.':</em>';
    } else {
      $output .= '<em class="filled">'.$name.':</em>';
    }
  } else {
    $output .= '<em class="notneeded">'.$name.':</em>';
  }
  $output .= '</td><td class="input" align="right">';
  $output .= '<input type="text" name="'.$form_name.'" size="'.$size.'" value="'.$fill.'" /></td></tr>';
  return $output;
}

function form_textfields($name,$descriptions,$form_names,$req,$fills,$sizes){
  $box_count = count($descriptions);

  $output =  '<tr><td class="label">';
  if($req == 1){
    $isfilled = true;
    foreach ($fills as $filling){
      if($filling == ""){
	$isfilled = false;
      }
    }
    if($isfilled == ""){
      $output .= '<em class="needed">'.$name.':</em>';
    } else {
      $output .= '<em class="filled">'.$name.':</em>';
    }
  } else {
    $output .= '<em class="notneeded">'.$name.':</em>';
  }
  $output .=  '</td><td class="input" align="right">';
  for ($i = 0; $i < $box_count; $i++){
    $output .= '<em class="smalltext">'.$descriptions[$i].'</em>';
    $output .= '<input type="text" name="'.$form_names[$i].'" size="'.$sizes[$i].'" value="'.$fills[$i].'" />';
  }
  $output .= '</td></tr>';
  return $output;
}

function form_list($name,$form_name,$req,$options,$selected,$update){

  $output = "";
  $output .=  '<tr><td class="label">';
  if($req == 1){
    if($selected == ""){
      $output .= '<em class="needed">'.$name.':</em>';
    } else {
      $output .= '<em class="filled">'.$name.':</em>';
    }
  } else {
    $output .= '<em class="notneeded">'.$name.':</em>';
  }
  $output .= '</td><td class="input" align="right">';
  $output .= '<select name="'.$form_name.'">';
  foreach ($options as $opt){
    if($opt == $selected){
      $output .= '<option selected="selected">'.$opt.'</option>';
    } else {
      $output .= '<option>'.$opt.'</option>';
    }
  }
  $output .= '</select>';
  if($update == 1){
    $output .= '<input type="submit" value="update" name="action">';
  }
  $output .= '</td></tr>';
  return $output;
}

function form_textarea($name,$form_name,$req,$fill,$cols,$rows){
  $output =  '<tr><td class="label">';
  if($req == 1){
    if($fill == ""){
      $output .= '<em class="needed">'.$name.':</em>';
    } else {
      $output .= '<em class="filled">'.$name.':</em>';
    }
  } else {
    $output .= '<em class="notneeded">'.$name.':</em>';
  }
  $output .= '</td><td class="input" align="right">';
  $output .= '<textarea name="'.$form_name.'" cols="'.$cols.'" rows="'.$rows.'"">'.$fill.'</textarea></td></tr>';
  return $output;
}

function format_mail($field_name,$data){
  return '<'.$field_name.'>'.$data.'</'.$field_name.'>
';
}

function preserve_info($field,$default){
  $variable = $default;
  try{
    $variable = $_POST[$field];
  } catch (Exception $e){
  }
  return $variable;
}


function spamcheck($field)
  {
  //filter_var() sanitizes the e-mail
  //address using FILTER_SANITIZE_EMAIL
  $field=filter_var($field, FILTER_SANITIZE_EMAIL);

  //filter_var() validates the e-mail
  //address using FILTER_VALIDATE_EMAIL
  if(filter_var($field, FILTER_VALIDATE_EMAIL))
    {
    return TRUE;
    }
  else
    {
    return FALSE;
    }
  }

function check_for_required_info(){
  global $given_names,$surname,$affiliation,
    $country,$email,$position,$gender,
    $day,$month,$year,$type,$title,$abstract,
    $n_authors,$authors,$n_affs,$affiliations,
    $n_refs,$references,$accommodation,
    $n_accs,$accompanyings;
    
  $ok = true;
  $message = "";
  
  $reqs = array( array( $given_names, "Given names missing." ),
		 array( $surname, "Surname missing." ),
		 array( $affiliation, "Affiliation missing." ),
		 array( $country, "Country missing." ),
		 array( $email, "E-mail address missing." ),
		 array( $position, "Academic position missing." ),
		 array( $gender, "Gender missing." ),
		 array( $day, "Day of birth missing." ),
		 array( $month, "Month of birth missing." ),
		 array( $year, "Year of birth missing." ),
		 array( $accommodation, "Accommodation choice missing." ),
		 array( $type, "Presentation type missing." ),
		 array( $title, "Presentation title missing." ),
		 array( $abstract, "Abstract missing." ) );
    
  for ($i = 0; $i < $n_accs; $i++){
    $reqs[] = array($accompanyings['gnames'][$i],"Given names of accompanying person ".($i+1)." missing.");
    $reqs[] = array($accompanyings['snames'][$i],"Surname of accompanying person ".($i+1)." missing.");
    $reqs[] = array($accompanyings['dobs'][$i],"Date of birth of accompanying person ".($i+1)." missing.");
  }
  for ($i = 0; $i < $n_authors; $i++){
    $reqs[] = array($authors['names'][$i],"Name of author ".($i+1)." missing.");
    $reqs[] = array($authors['initials'][$i],"Initials of author ".($i+1)." missing.");
    $reqs[] = array($authors['affs'][$i],"Affiliation of author ".($i+1)." missing.");
  }
  for ($i = 0; $i < $n_affs; $i++){
    $reqs[] = array($affiliations[$i],"Affiliation ".($i+1)." missing.");
  }
  for ($i = 0; $i < $n_refs; $i++){
    $reqs[] = array($references['authors'][$i],"Reference ".($i+1)." authors missing.");
    $reqs[] = array($references['journal'][$i],"Reference ".($i+1)." journal missing.");
    $reqs[] = array($references['year'][$i],"Reference ".($i+1)." year missing.");
    $reqs[] = array($references['page'][$i],"Reference ".($i+1)." page missing.");
  }

  foreach ($reqs as $required){
    if($required[0] == ""){
      $ok = false;
      $message .= $required[1]."<br />";
    }
  }
  
  if(spamcheck($email) == FALSE){
    $ok = false;
    $message .= "Invalid email address<br />";
  }
  
  return array( "ok" => $ok, "message" => $message );
    
}


$given_names = preserve_info('given_names',"");
$surname = preserve_info('surname',"");
$affiliation = preserve_info('affiliation',"");
$address = preserve_info('address',"");
$country = preserve_info('country',"");
$email = preserve_info('email',"");
$position = preserve_info('position',"");
$gender = preserve_info('gender',"");
$day = preserve_info('day',"");
$month = preserve_info('month',"");
$accommodation = preserve_info('accommodation',"");
$roommate = preserve_info('roommate',"");
$year = preserve_info('year',"");
$misc = preserve_info('misc',"");
$title = preserve_info('title',"");
$type = preserve_info('type',"");
$abstract = preserve_info('abstract',"");


$n_accs = preserve_info('accompanying_count',0);
$accompanyings = array('gnames' => array($n_accs),
		      'snames' => array($n_accs),
		      'dobs' => array($n_accs));
for ($i=0; $i < $n_accs; $i++){
  $accompanyings['gnames'][$i] = preserve_info('gname_'.($i+1),"");
  $accompanyings['snames'][$i] = preserve_info('sname_'.($i+1),"");
  $accompanyings['dobs'][$i] = preserve_info('dob_'.($i+1),"");
}

$n_authors = preserve_info('author_count',1);
$authors = array('names' => array($n_authors),
		 'initials' => array($n_authors),
		 'affs' => array($n_authors));
for ($i=0; $i < $n_authors; $i++){
  $authors['names'][$i] = preserve_info('authors_'.($i+1),"");
  $authors['initials'][$i] = preserve_info('initials_'.($i+1),"");
  $authors['affs'][$i] = preserve_info('affs_'.($i+1),"");
}

$n_affs = preserve_info('aff_count',1);
$affiliations = array($n_affs);
for ($i=0; $i < $n_affs; $i++){
  $affiliations[$i] = preserve_info('affiliations_'.($i+1),"");
}


$n_refs = preserve_info('ref_count',0);
$references = array('authors' => array($n_refs),
		    'journal' => array($n_refs),
		 'volume' => array($n_refs),
		 'year' => array($n_refs),
		 'page' => array($n_refs));
for ($i=0; $i < $n_refs; $i++){
  $references['authors'][$i] = preserve_info('auths_'.($i+1),"");
  $references['journal'][$i] = preserve_info('journal_'.($i+1),"");
  $references['volume'][$i] = preserve_info('volume_'.($i+1),"");
  $references['year'][$i] = preserve_info('year_'.($i+1),"");
  $references['page'][$i] = preserve_info('page_'.($i+1),"");
}

$preview = false;
$try_send = false;
$check = array( "ok" => false, "message" => "" );
if ( 'POST' == $_SERVER['REQUEST_METHOD'] ){
  $check = check_for_required_info();
  if( $_POST['action'] == 'preview' ){
    $preview = true;
  }  
  if( $_POST['action'] == 'send' ){
    $try_send = true;
  }
}



$acccounter = array(0 => 0);
for ($i=1; $i < $n_accs+10; $i++){
  $acccounter[] = $i;
}
$authorcounter = array(0 => 1);
for ($i=2; $i < $n_authors+10; $i++){
  $authorcounter[] = $i;
}
$affcounter = array(0 => 1);
for ($i=2; $i < $n_affs+10; $i++){
  $affcounter[] = $i;
}
$refcounter = array(0 => 0);
for ($i=1; $i < $n_refs+10; $i++){
  $refcounter[] = $i;
}

$page_contents = $html_header;

$page_contents .= '<h1>Registration</h1>';

$page_contents .= '<p class="instructions">';
$page_contents .= 'Please register to the workshop <b>Physics boat 2019</b> by filling the form below. Make sure to fill in all the <b>required fields</b>, shown in bold text.';
$page_contents .= '</p><br />';

$page_contents .= '<p class="instructions">';
$page_contents .= 'The deadline for registration is <b>2019-03-29, the 29th of March 2019</b>.';
$page_contents .= '</p><br />';

$page_contents .= '<p class="instructions">';
$page_contents .= 'Registration requires a submission of an abstract. The abstract has to be submitted through the form by writing or copying the information to the given fields. This is to ensure a uniform formatting of the submitted abstracts. For writing mathematics and special characters, <b>use LaTeX typesetting</b>. (Like so: <code>$C_{60}$</code> for $C_{60}$, <code>$1 \le 2$</code> for $1 \le 2$, <code>$\sqrt{\alpha}$</code> for $\sqrt{\alpha}$ etc. Remember to wrap the LaTeX in dollar signs.) Avoid writing special characters directly since they may get corrupted. Attaching figures is not allowed.';
$page_contents .= '</p><br />';

$page_contents .= '<p class="instructions">';
$page_contents .= 'Decision on the acceptance of your abstract will be sent by email by <b>2019-04-08</b>.';
$page_contents .= '</p><br />';

if($preview){
  $page_contents .= '<p class="notify">';
  $page_contents .= '<b>A preview of your submission is shown below the registration form.</b>';
  $page_contents .= '</p>';
}

if($check["message"] != ""){
  $page_contents .= '<p class="notify">';
  $page_contents .= '<b>The following information is needed to complete the submission:</b><br />'.$check["message"];
  $page_contents .= '</p>';
}

$sent_successfully = false;

if($try_send){
  //$official_sender = 'Arkady Krasheninnikov <arkady.krasheninnikov@aalto.fi>';
  //$regmail_to = 'Adam Foster <adam.foster@aalto.fi>, Arkady Krasheninnikov <arkady.krasheninnikov@aalto.fi>, Peter Spijker <peter.spijker@aalto.fi>, Teemu Hynninen <teemu.hynninen@utu.fi>';
  //$regmail_to = 'Peter Spijker <peter.spijker@aalto.fi>';
  $official_sender = 'Physics Boat site <viestiphysics@aalto.fi>';
  $regmail_to = 'Physics Boat Workshop <physicsboat@list.aalto.fi>';
  //$regmail_to = 'Physics Boat Workshop <hannu-pekka.komsa@aalto.fi>';
  //$regmail_headers = 'From: '.$given_names.' '.$surname.' <'.$email.'>';
  $regmail_headers = 'From: Physics Boat site <viestiphysics@aalto.fi>';
  $regmail_title = '[Boat2019] Registration by '.$given_names.' '.$surname;

  $regmail_contents = format_mail('given names',$given_names);
  $regmail_contents .= format_mail('surname',$surname);
  $regmail_contents .= format_mail('affiliation',$affiliation);
  $regmail_contents .= format_mail('address',$address);
  $regmail_contents .= format_mail('country',$country);
  $regmail_contents .= format_mail('email',$email);
  $regmail_contents .= format_mail('position',$position);
  $regmail_contents .= format_mail('gender',$gender);
  $regmail_contents .= format_mail('day of birth',$day);
  $regmail_contents .= format_mail('month of birth',$month);
  $regmail_contents .= format_mail('year of birth',$year);
  $regmail_contents .= format_mail('accommodation',$accommodation);
  $regmail_contents .= format_mail('roommate',$roommate);
  $regmail_contents .= format_mail('misc info',$misc);
  $regmail_contents .= format_mail('number of guests',$n_accs);
  for ($i = 0; $i < $n_accs; $i++){
    $regmail_contents .= format_mail('guest '.($i+1).' given name',$accompanyings['gnames'][$i]);
    $regmail_contents .= format_mail('guest '.($i+1).' surname',$accompanyings['snames'][$i]);
    $regmail_contents .= format_mail('guest '.($i+1).' birthday',$accompanyings['dobs'][$i]);
  }
  $regmail_contents .= format_mail('type',$type);
  $regmail_contents .= format_mail('title',$title);
  $regmail_contents .= format_mail('number of authors',$n_authors);
  for ($i = 0; $i < $n_authors; $i++){
    $regmail_contents .= format_mail('author '.($i+1).' initials',$authors['initials'][$i]);
    $regmail_contents .= format_mail('author '.($i+1).' name',$authors['names'][$i]);
    $regmail_contents .= format_mail('author '.($i+1).' affiliation',$authors['affs'][$i]);
  }
  $regmail_contents .= format_mail('number of affiliations',$n_affs);
  for ($i = 0; $i < $n_affs; $i++){
    $regmail_contents .= format_mail('affiliation '.($i+1),$affiliations[$i]);
  }
  $regmail_contents .= format_mail('abstract',$abstract);
  $regmail_contents .= format_mail('number of references',$n_refs);
  for ($i = 0; $i < $n_refs; $i++){
    $regmail_contents .= format_mail('reference '.($i+1).' authors',$references['authors'][$i]);
    $regmail_contents .= format_mail('reference '.($i+1).' journal',$references['journal'][$i]);
    $regmail_contents .= format_mail('reference '.($i+1).' volume',$references['volume'][$i]);
    $regmail_contents .= format_mail('reference '.($i+1).' year',$references['year'][$i]);
    $regmail_contents .= format_mail('reference '.($i+1).' page',$references['page'][$i]);
  }

  $sent_successfully = mail($regmail_to, 
			    $regmail_title,
			    $regmail_contents,
			    $regmail_headers );


  if($n_accs > 1){
    //$notemail_headers = 'From: '.$email;
    $notemail_headers = 'From: Physics Boat site <viestiphysics@aalto.fi>';
    $notemail_title = '[Boat2019] Notification on '.$given_names.' '.$surname;
    $notemail_contents = 'Notice! '.$given_names.' '.$surname.' registered '.$n_accs.' accompanying people. Contact for details!';

    $sent_notice = mail($regmail_to, 
			$notemail_title,
			$notemail_contents,
			$notemail_headers );
  }

  $prefix = "";
  if($position == "student"){
    if($gender == "male"){
      $prefix = "Mr. ";
    } else if($gender == "female"){
      $prefix = "Mrs. ";
    }
  } else if($position == "post-doc"){
    $prefix = "Dr. ";
  } else if($position == "senior"){
    $prefix = "Dr. ";
  } else if($position == "professor"){
    $prefix = "Prof. ";
  }

  $confmail_to = $email;
  $confmail_title = "Your registration";
  $confmail_headers = "From: ".$official_sender;
  $confmail_contents = "Dear ".$prefix.''.$given_names.' '.$surname.",

You have successfully registered to the Physics Boat 2019 workshop with the following information.

";
  
  $confmail_contents .= 'Name: '.$given_names.' '.$surname.'
Affiliation: '.$affiliation;
  if($address != ""){
    $confmail_contents .= ', '.$address;
  }
  $confmail_contents .= ', '.$country.'
Position: '.$position.'
Gender: '.$gender.'
Date of birth: '.$day.'/'.$month.'/'.$year.'
Accommodation: '.$accommodation.'
';

  for($i = 0; $i < $n_accs; $i++){
      $confmail_contents .= 'Accompanying person '.($i+1).': '.$accompanyings['gnames'][$i].' '.$accompanyings['snames'][$i].' '.$accompanyings['dobs'][$i].'
';
  }
  
  if($roommate != ""){
    $confmail_contents .= 'Roommate: '.$roommate.'
';
  }

  if($misc != ""){
    $confmail_contents .= 'Additional: '.$misc.'
';
  }

  $confmail_contents .= 'Abstract ('.$type.'):

'.$title.'

';
  
  for($i=0; $i<$n_authors; $i++){
    $confmail_contents .= $authors['initials'][$i].' '.$authors['names'][$i].' ('.$authors['affs'][$i].')';
    if($i < $n_authors-1){
      $confmail_contents .= ', ';
    }
  }
  $confmail_contents .= '
';
  for($i=0; $i<$n_affs; $i++){
    $confmail_contents .= '  '.($i+1).': '.$affiliations[$i].'
';
  }
  
  $confmail_contents .= '

'.$abstract.'

';
  for($i=0; $i<$n_refs; $i++){
    $confmail_contents .= ' ['.($i+1).'] '.$references['authors'][$i].', '.$references['journal'][$i].' '.$references['volume'][$i].' ('.$references['year'][$i].') '.$references['page'][$i].'.
';
  }
  
  $confmail_contents .= "

If you have not meant to register and have received this message by mistake, please contact ".$official_sender.'.

Best Regards,

Physics Boat Workshop organizers
--------------------------------
Arkady Krasheninnikov
Hannu-Pekka Komsa
';

  $sent_confirm = mail($confmail_to, 
		       $confmail_title,
		       $confmail_contents,
		       $confmail_headers );

}


if($sent_successfully){

} else {

  $page_contents .= '<form method="post" action="register.php">';
  
  $page_contents .= '<h2>Personal information</h2>';
  $page_contents .= "<table><tbody>";
  
  // Name
  $page_contents .= form_textfields("Name",
				    array("given names","surname"),
				    array("given_names","surname"),
				    1,
				    array($given_names,$surname),
				    array(20,20));
  // Affiliation
  $page_contents .= form_textfield("Affiliation",
				   "affiliation",
				   1,
				   $affiliation,
				   70);
  
  // Address
  $page_contents .= form_textfield("Address",
				   "address",
				   0,
				   $address,
				   70);
  
  // Country
  $page_contents .= form_textfield("Country",
				   "country",
				   1,
				   $country,
				   70);
  
  // Email
  $page_contents .= form_textfield("E-mail",
				   "email",
				   1,
				   $email,
				   70);
  
  // Position
  $page_contents .= form_list("Position",
			      "position",
			      1,
			      array("","student","post-doc","senior","professor"),
			      $position,
			      0);
  
  // Gender
  $page_contents .= form_list("Gender",
			      "gender",
			      1,
			      array("","female","male"),
			      $gender,
			      0);
  
  // Date of birth
  $page_contents .= form_textfields("Date of birth",
				    array("day","month","year"),
				    array("day","month","year"),
				    1,
				    array($day,$month,$year),
				    array(10,10,10));
  
  $page_contents .= form_info("The date of birth is required by the company which operates the ferry (Tallink). This information will be given to the ferry company only and will not be disclosed anywhere else.");
  
  $shared = "shared double cabin: 250 e";
  $single = "single cabin: 350 e";
  $guest = "single cabin + accompanying person: 450 e";
  $many = "several accompanying people";

  // Accommodation
  $page_contents .= form_list("Accommodation",
			      "accommodation",
			      1,
			      array("",$shared,$single,$guest,$many),
			      $accommodation,
			      1);
  
  $page_contents .= form_info("The accommodation cost depends on whether you share a cabin or not. You should also inform us if you plan to bring an accompanying person (or several) with you.");


  if($accommodation == $shared){
    // Roommate
    $page_contents .= form_textfield("Suggest roommate",
				     "roommate",
				     0,
				     $roommate,
				     70);    
    $page_contents .= form_info("You may suggest someone to share the cabin with.");
    $n_accs = 0;
  }

  if($accommodation == $single){
    $n_accs = 0;
  }

  
  if($accommodation == $guest or $accommodation == $many){

    if($accommodation == $guest){
      $n_accs = 1;
    }
    if($accommodation == $many && $n_accs < 2){
      $n_accs = 2;
    }
    // Accompanying people
    $page_contents .= form_list("Number of accompanying people",
				"accompanying_count",
				0,
				$acccounter,
				$n_accs,
				1);
  
    for ($i=0; $i < $n_accs; $i++){
      $page_contents .= form_textfields("Accompanying person ".($i+1),
					array("given names","surname","date of birth (ddmmyyyy)"),
					array("gname_".($i+1),"sname_".($i+1),"dob_".($i+1)),
					1,
					array($accompanyings['gnames'][$i],$accompanyings['snames'][$i],$accompanyings['dobs'][$i]),
					array(10,10,10));
    }
  
    
    if($accommodation == $many ){
      $page_contents .= form_info("If more than one person is going to accompany you, accommodation in a cabin for 4 people can be arranged. <em class='notice'>If you list several accompanying people, we will contact you via e-mail to discuss the accommodation options and prices.</em>");
    }
  
  }

  // Message
  $page_contents .= form_textfield("Requests or other info",
				   "misc",
				   0,
				   $misc,
				   70);  
  
  $page_contents .= "</table></tbody>";
  $page_contents .= '<h2>Contribution</h2>';
  $page_contents .= "<table><tbody>";
  
  
  // Presentation
  $page_contents .= form_list("Preferred presentation",
			      "type",
			      1,
			      array("","oral presentation preferred","poster presentation only"),
			      $type,
			      0);
  
  // Title
  $page_contents .= form_textfield("Title",
				   "title",
				   1,
				   $title,
				   70);
  
  // Authors
  $page_contents .= form_list("Number of authors",
			      "author_count",
			      1,
			      $authorcounter,
			      $n_authors,
			      1);
  
  for ($i=0; $i < $n_authors; $i++){
    $page_contents .= form_textfields("Author ".($i+1),
				      array("initials","surname","affiliations"),
				      array("initials_".($i+1),"authors_".($i+1),"affs_".($i+1)),
				      1,
				      array($authors['initials'][$i],$authors['names'][$i],$authors['affs'][$i]),
				      array(10,20,10));
  }
  $page_contents .= form_info("Give the initials and surnames for all contributing authors. 
Also mark the affiliations as comma-separated numbers, e.g, '1,3'. 
Make sure not to use non-standard characters, as they will likely not be reproduced correctly. Instead, <b>use LaTeX formatting</b> for special symbols.");
  
  // Affiliations
  $page_contents .= form_list("Number of affiliations",
			      "aff_count",
			      1,
			      $affcounter,
			      $n_affs,
			      1);
  
  for ($i=0; $i < $n_affs; $i++){
    $page_contents .= form_textfield("Affiliation ".($i+1),
				     "affiliations_".($i+1),
				     1,
				     $affiliations[$i],
				     70);
  }
  
  // Abstract
  $page_contents .= form_textarea("Abstract",
				  "abstract",
				  1,
				  $abstract,
				  60,10);
  $page_contents .= form_info("Copy and paste your abstract here. Make sure not to use non-standard characters, as they will likely not be reproduced correctly. Instead, <b>use LaTeX formatting</b> for special symbols and mathematical expressions.");
  
  
  // References
  $page_contents .= form_list("Number of references",
			      "ref_count",
			      1,
			      $refcounter,
			      $n_refs,
			      1);
  
  for ($i=0; $i < $n_refs; $i++){
    $page_contents .= form_textfields("Reference ".($i+1),
				      array("authors","journal","volume","year","page"),
				      array("auths_".($i+1),"journal_".($i+1),"volume_".($i+1),"year_".($i+1),"page_".($i+1)),
				      1,
				      array($references['authors'][$i],$references['journal'][$i],$references['volume'][$i],$references['year'][$i],$references['page'][$i]),
				      array(10,15,3,3,3));
  }
  
  $page_contents .= form_info("Cite references in the abstract using numbers and square brackets, e.g., '[1,2]'. For journals, give the volume, year and page. For submitted or accepted articles, please write, e.g., 'submitted' or give the DOI in the 'page' field and put this year in the 'year' field. For books and other types, give the name in the 'journal' field, year in the 'year' field, and other details in the 'page' field.");
  
}

if($preview || $sent_successfully){
  
  if($preview){
    $page_contents .= "</td></tr>";
    $page_contents .= "</tbody></table>";
    $page_contents .= '<h2>Submission preview</h2>';
  } else {

    $page_contents .= '<p class="notify">';
    $page_contents .= '<b>Your submission was sent successfully. A confirmation was sent to '.$email.'</b>';
    $page_contents .= '</p>';

    $page_contents .= '<h2>Submission complete</h2>';
  }
  
  $prefix = "";
  if($position == "student"){
    if($gender == "male"){
      $prefix = "Mr. ";
    } else if($gender == "female"){
      $prefix = "Mrs. ";
    }
  } else if($position == "post-doc"){
    $prefix = "Dr. ";
  } else if($position == "senior"){
    $prefix = "Dr. ";
  } else if($position == "professor"){
    $prefix = "Prof. ";
  }

  $page_contents .= '<p class="info">';
  $page_contents .= 'Name: '.$prefix.''.$given_names.' '.$surname.'</br />';
  $page_contents .= 'Affiliation: '.$affiliation;
  if($address != ""){
    $page_contents .= ', '.$address;
  }
  $page_contents .= ', '.$country.'<br />';
  $page_contents .= 'E-mail: '.$email.'<br />';
  $page_contents .= 'Date of birth: '.$day.'/'.$month.'/'.$year.'<br />';
  $page_contents .= 'Accommodation: '.$accommodation.'<br />';
  for($i = 0; $i < $n_accs; $i++){
    $page_contents .= 'Accompanying person '.($i+1).': '.$accompanyings['gnames'][$i].' '.$accompanyings['snames'][$i].' '.$accompanyings['dobs'][$i].'<br />';
  }
  if($roommate != ""){
    $page_contents .= 'Roommate: '.$roommate.'<br />';
  }
  $page_contents .= '</p>';

  $page_contents .= '<p class="info">';
  $page_contents .= 'Abstract ('.$type.'):';
  $page_contents .= '</p>';


  $page_contents .= '<p class="title">'.$title.'</p>';
  $page_contents .= '<p class="authors">';
  for ($i = 0; $i < $n_authors; $i++){
    if($authors['names'][$i] == $surname){
      $page_contents .= "<u>";
    }
    $page_contents .= $authors['initials'][$i]." ".$authors['names'][$i]."<sup>".$authors['affs'][$i]."</sup>";
    if($authors['names'][$i] == $surname){
      $page_contents .= "</u>";
    }    
    if($i < $n_authors-1){
      $page_contents .= ", ";
    }
  }
  $page_contents .= '</p>';
  $page_contents .= '<p class="affiliations">';
  for ($i = 0; $i < $n_affs; $i++){
    $page_contents .= '<sup>'.($i+1).'</sup>'.$affiliations[$i].'.<br />';
  }
  $page_contents .= '</p>';
  
  $page_contents .= '<p class="abstract">'.nl2br($abstract,true).'</p>';
  
  $page_contents .= '<p class="references">';
  for ($i = 0; $i < $n_refs; $i++){
    //    $page_contents .= '['.($i+1).'] '.$references['journal'][$i];
    $page_contents .= '['.($i+1).'] '.$references['authors'][$i].', '.$references['journal'][$i].' <b>'.$references['volume'][$i].'</b> ('.
      $references['year'][$i].') '.$references['page'][$i].'.<br />';
  }
  $page_contents .= '</p>';

  if($check["ok"]){
    if($sent_successfully){
      $page_contents .= '<p class=info><a href="http://physics.aalto.fi/pub/boat/">back to main page</a></p>';
    } else {
      $page_contents .= '<table><tbody><tr><td><input type="submit" value="preview" name="action"><input type="submit" value="send" name="action"></td></tr></tbody></table>';
    }
  } else {
    $page_contents .= '<table><tbody><tr><td><input type="submit" value="preview" name="action"></td></tr></tbody></table>';
  }     
} else {
  $page_contents .= '<tr><td><input type="submit" value="preview" name="action"></td></tr>';
  $page_contents .= "</tbody></table>";
}
$page_contents .= '</form>';
$page_contents .= $html_tail;

print $page_contents;

?> 
