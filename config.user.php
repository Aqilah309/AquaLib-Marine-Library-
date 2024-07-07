<?php

/*
sWADAH config default file build 20220425

The following php extension need to be enable in php.ini:
bz2,curl,fileinfo,gd or gd2,gettext,intl,mbstring,exif,mysqli,openssl,pdo_mysql,pdo_sqlite
After uncomment the line to enable those above, restart Apache.

Everything in this sea of codes ARE cAsE SENsitIVE.

Read carefully all comments before proceeding. Failure and success at your own risks.

Extra note: You might want to copy value you want to change and put it into config.user.php (you can create it manually within the same directory as config.default.php) 
so that you don't have to revalue config.php everytime there is new build of sWADAH.
*/

//set time zone for this sWADAH installation
putenv("TZ=Asia/Kuala_Lumpur");//set time zone reference: https://en.wikipedia.org/wiki/List_of_tz_database_time_zones
date_default_timezone_set('Asia/Kuala_Lumpur');//set php time zone reference: https://www.php.net/manual/en/timezones.php

//database connection properties   
$dbhost = "localhost";//set the ip or host for mariadb/mysql database
$dbname = "swadah_db";//database name to access
$dbuser = "root";//username to access the database
$dbpass = "aqilahf611";//password the username above

/*
AES KEY
=======
you will need to very careful and do not change the $password_aes_key mid way when using the system as your user not be able to login 
unless you revert back to the old key. set this once and never again edit it.
default: 45C799DB3EBC65DFBC69A0F36F605E6CA2447CD519C50B7DA0D0D45D2B0F2431
*/
$aes_key_warning = true;//provide warning if aes key is the same as default upon installation. for new installation set this as true, for old installation set it as false
$password_aes_key = "aqilahf611";

//allowed IP for administration page
//only ip in this range are allowed to access administration page
//format range 10.x.x or 10.x or specific: 10.x.x.x
$restriction_for_adminPage = true; // enable allowed_ip below to enforce = true; disable = false.
$allowed_ip = array(
    '::1',
    '127.0.0.1'
);

//system function >> repo : repository only | depo : self deposit mode only | full : self deposit and repository | photo : photo repository
$system_function = 'full';

/*
system running mode live|demo|maintenance 
in 'demo' mode: password changing, user management module will be disabled.
in 'maintenance' mode: the searcher and user portion of this system will be disabled.    
*/
$system_mode = "live";    

//set installed date for this IR
//must be the original installed date of sWadah, repository data will cannot be any lower than this 
//format YYYY-MM-dd
$installed_date = "2018-01-01";

//system title - give a title to your sWADAH installation
$system_title = "AquaLib";

/*
system indentifier (preferaby a domain without leading http or https) -- for in use at oai-pmh module
*/
$system_identifier = "aqualib.my";

/*
repository url (mention the subdirectory, if applicable)
must begin with http:// or https:// and end with /
eg. https://myir.myuni.edu/myrepo/ (if subdirectory) or https://myir.myuni.edu/ (if no subdirectory)
please take note this value will affect many part in the system codes. please make sure it has a correct value.
*/
$system_path = "https://localhost/sWADAH/";

//repository policy url
$system_policy_url = "https://localhost/sWADAH//policy.pdf";

//this server ip address (internal network ip only)
$system_ip = "192.168.12.153";

//ezproxy server ip address (internal network ip only)
//set your ezproxy ip server here (if you have no ezproxy server, just type in your server ip address here)
//if sWADAH detected user access from ezproxy, the sWADAH automagically will enable full text access (if permittable)
$ezproxy_ip = "192.168.12.153";
$ezproxy_appended_domain = "localhost_sWADAH.ezproxy.sWADAH";//url that appended by ezproxy for this repository. it might look something like this sample url. http or https will not be required.

//admin email
$system_admin_email = "user@gmail.com";

//admin contact disclaimer 
$system_admin_contact_disclaimer = "If you have enquiries with this repository, kindly contact us at <a href='mailto:aqualib@gmail.com'>aqualib@gmail.com</a> or 011-59594130";

//registered owner
$system_owner = "AquaLib";

//helpdesk info for more information
$system_helpdesk_contact = "AquaLib Support Unit support line 06-6771411.";

//searcher access only for permitted IP addresses/range input using Administration > Allowed IP configuration // default is false to allow all
$ip_restriction_enabled = false;

//logo and icons
//you may use custom folder(s) to store your own logos, images and icons
$main_logo = "sw_images/AquaLib.jpg";//default is sw_images/company.png
    $main_logo_width = "200px";//can be px or %
$browser_icon = "sw_images/AquaLib_www-icon.jpg";
$menu_icon = "sw_images/AquaLib_big-icon.jpg";

//intro words below the main logo / html5 enabled. you may use html tag on it to control size etc.
$intro_words = "<div style='margin-top:10px;font-size:12px;'>Welcome to AquaLib Digital Repository</div>";

//control footer font size. in px.
$footer_fontSize = "8px";

//disclaimer or copyright info
$copyright_info = "This material may be protected under Copyright Act which governs the making of photocopies or reproductions of copyrighted materials.<br/>You may use the digitized material for private study, scholarship, or research.";

//cache directory for statistic generator
$system_statcache_directory = "files/statcache";

//system intruder detection mode for all guest enabled page such as searcher.php and all title listers: subject heading, year, publisher browsers
//strict =user input will be sanitize AND will automatically block access to all related pages within even one try of invalid access/query
//precaution =user input will be sanitize AND will automatically block access to all related pages within 5 invalid accesses/queries
//guard =default, user input will be sanitize and will not be block until reached a hard limit of 99 invalid queries. should he/she cross that line, the access will be blocked. redirection to default page will be enforced.
//all above incidents are stored in files/blocked/..
//all bans will only valid for one day. will be automatically cleared the next day.
$invalid_access_detection = "precaution";// strict|precaution|guard
$blocked_file_location = "files/blocked";//directory without the leading and following / you have to make sure the directory is apache rewritable

//full text document extension for uploading new item
$system_docs_directory = "files/docs";//directory without the leading and following / you have to make sure the directory is apache rewritable
$system_allow_document_extension = "pdf";
$system_allow_document_maxsize = "100";//in MB
$max_allow_parser_to_work = "20";//in MB, anything bigger pdf will not be indexed.
$allow_guest_access_to_ft = true; //allow guest access to full text document
$index_pdf = true;//set whether want to index full text pdf contents or not: true | false

//guest document extension for uploading new item
$system_pdocs_directory = "files/pdocs";//directory without the leading and following / you have to make sure the directory is apache rewritable
$system_allow_pdocument_extension = "pdf";
$system_allow_pdocument_maxsize = "20";//in MB
$allow_guestpdf_insert_by_admin = true;//allow guest file input to be inserted for an item
$allow_guest_access_to_pt = false;//allow guest access to guest document

//index extension for uploading new item
$system_txts_directory = "files/txts";//directory without the leading and following / you have to make sure the directory is apache rewritable
$system_allow_txt_extension = "txt";
$system_allow_txt_maxsize = "5";//in MB
$allow_txt_insert_by_admin = true;//allow text file input to be inserted

//image attachment extension for uploading new item
$system_albums_directory = "files/albums";//directory without the leading and following / you have to make sure the directory is apache rewritable
$watermark_overlay_file = "sw_images/watermark.png"; //in use for image upload, will be automatically watermarked, must be transparent and png
$system_albums_thumbnail_directory = "files/albums_thumbnailed";
$system_albums_watermark_directory = "files/albums_watermarked";
$system_allow_imageatt_extension = "jpg,jpeg";
$system_allow_imageatt_maxsize = "10";//in MB
$maximum_num_imageatt_allowed = 16;//maximum number of additional image attachment allowed
$allow_image_insert_by_admin = true;//allow image file input to be inserted

//for paging purposes, how many number per page per resultset
$system_wide_resultPerPage = 20;

//subject heading
    //enable subject heading selection in input and update page
    $enable_subject_entry = true;

    //rebrand subject heading as -  this will utilize/repurpose subject heading for a different means
    $subject_heading_as = "Subject";
    $subject_heading_delimiter = ",";//to be set before any first input of data

    //selectable subject heading : single|multi
    //single - only one subject heading per item
    //multi - user may select multiple subject headings per item
    $subject_heading_selectable = "multi";

//show browser bar
$show_browser_bar_guest = true;//allow or not guest to access browser bar
$show_browser_bar_admin = true;//allow or not admin to access browser bar
    //browser bar childs
    $show_subject_browser_bar = true;//allow or not subject browser to be shown
    $show_publisher_browser_bar = true;//allow or not publisher browser to be shown
    $show_year_browser_bar = true;//allow or not year browser to be shown

//user page
$allow_user_to_login = true;//allow user to login to their page, enable the My Account button and Login link

//show admin login link on start page
$show_admin_login_link = true; //admin login link on meta. if you set this to false, to access is by calling 'in.php'

//tutorial - enable tutorial button for self deposit
$enable_tutorial_button = false;// to enable set to true and then..
$tutorial_link = "";//link must include complete URL with http or https

//searcher
$searcher_type_bar_visibility = true;//search input bar on index page
$searcher_marker_to_hide = null;//default = null (in use when copying from eprints import), you may enter any string to automatically hide them when entering search engine
$searcher_show_frequency_of_toprateditems = false;//true will show popular search items on searcher page.
$searcher_title_font_size = "16px";//title size in pixel
$searcher_author_font_size = "14px";//author size in pixel
$searcher_hits_font_size = "10px";//hits count size in pixel

//document viewer and downloader
$max_download_allowed = "3";//the number of count digital document will be available before it is expired (per user session)
$max_time_link_availability = "86400";//duration in seconds on which the digital document will be available before it is expired.

//searcher icon indicator below the title description
$searcher_show_icon_indicator = true;// show (true) or hide (false) indicators on guest search page for results

//show or hide qr code on item detail page
$show_qr_code_for_item = false;

/*
item delete method : permanent | takecover
permanent: item and all its detailing will be deleted entirely from the system
takecover : item will be set to undiscoverable and resources will be renamed to <filename>.<extension>.deleted
*/
$delete_method = "permanent";

//hide/show initial status on add new item page. if hide, default will always be Available-Public
$init_status_visibility = "show";

//embargoed duration. if item is set it status to embargo, how long it would take to automatically set it back to available. in days.
$embargoed_duration = 365;//in days

/*
default view for input : simple | marc
please select either marc or simple on first run. you may experience data lost if try to convert to one another midway of using this system.
marc = input will based on selected marc records
*/
$default_view_input =  'simple';

//accession number visibility
$show_accession_number = true;

//control number visibility
$show_control_number = true;

/*
cataloguing tag formatting --start
setting 'show' to 'false' will render the field with an empty data even if you have put in data in it prior setting it to 'false'
please be caution and make sure to finalize your tags selection via institution policy on the first entry of data
*/

//38isbnissn 020
$tag_020_simple = "ISBN";    
$tag_020 = "$tag_020_simple <span style='color:green;'>020</span>";    
$tag_020_show = true;

$tag_022_simple = "ISSN";
$tag_022 = "$tag_022_simple <span style='color:green;'>022</span>";
$tag_022_show = true;

//38langcode 041
$tag_041_simple = "Language Code";
$tag_041 = "$tag_041_simple <span style='color:green;'>041</span>";
$tag_041_inputtype = "select"; //keyin|select
$tag_041_selectable = "zsm|eng|chi|tam|ara";//values must be separated by |
$tag_041_selectable_default = "zsm";//values must one of the above value
$tag_041_show = true;

//38localcallnum 090
$tag_090_simple = "Call Num";
$tag_090 = "$tag_090_simple <span style='color:green;'>090</span>";
$tag_090_show = true;

//38author 100
$tag_100_simple = "Main Author";
$tag_100 = "$tag_100_simple <span style='color:green;'>100</span>";
$tag_100_default_ind = "0#";
$tag_100_show = true;//must set to true

//38title 245
$tag_245_simple = "Title";
$tag_245 = "$tag_245_simple <span style='color:green;'>245</span>";
$tag_245_default_ind = "10";
$tag_245_show = true;//must set to true

//38vtitle 246
$tag_246_simple = "Varying Form of Title";
$tag_246 = "$tag_246_simple <span style='color:green;'>246</span>";
$tag_246_show = true;
$tag_246_default_ind = "";

//38edition 250
$tag_250_simple = "Edition";
$tag_250 = "$tag_250_simple <span style='color:green;'>250</span>";
$tag_250_show = true;

//38publication 264
$tag_264_simple = "Publication";
$tag_264 = "$tag_264_simple <span style='color:green;'>264</span>";
$tag_264_show = true;
$tag_264_a_default = "Tanjong Malim";
$tag_264_default_ind = "##";
$publisher_as = "Publisher"; //rebrand publisher as  -  this will utilize/repurpose publisher for a different means

//38physicaldesc 300
$tag_300_simple = "Physical Description";
$tag_300 = "$tag_300_simple <span style='color:green;'>300</span>";
$tag_300_show = true;
$tag_300_default_ind = "##";

//38contenttype 336
$tag_336_simple = "Content Type";
$tag_336 = "$tag_336_simple <span style='color:green;'>336</span>";
$tag_336_show = true;
$tag_336_default_a = "still image";
$tag_336_default_2 = "rdacontent";
$tag_336_default_ind = "##";
//38mediatype 337
$tag_337_simple = "Media Type";
$tag_337 = "$tag_337_simple <span style='color:green;'>337</span>";
$tag_337_show = true;
$tag_337_default_a = "computer";
$tag_337_default_2 = "rdamedia";
$tag_337_default_ind = "##";
//38carriertype 338
$tag_338_simple = "Carrier Type";
$tag_338 = "$tag_338_simple <span style='color:green;'>338</span>";
$tag_338_show = true;
$tag_338_default_a = "online resource";
$tag_338_default_2 = "rdacarrier";
$tag_338_default_ind = "##";
    
//38series 490
$tag_490_simple = "Series";
$tag_490 = "$tag_490_simple <span style='color:green;'>490</span>";
$tag_490_show = true;

//38notes 500
$tag_500_simple = "Notes";
$tag_500 = "$tag_500_simple <span style='color:green;'>500</span>";
$tag_500_hint = "";
$tag_500_show = true;
$tag_500_default_ind = "##";

//38dissertation_note 500
$tag_502_simple = "Degree Type";
$tag_502 = "Dissertation Note <span style='color:green;'>502</span>";
$tag_502_show = true;
$tag_502_inputtype = "select"; //keyin|select
$tag_502_b_selectable = "Doctoral|Masters|First Degree|Diploma|Others";//values must be separated by |
$tag_502_b_selectable_default = "First Degree";//values must one of the above value

//38restriction 506 -available for depositor mode only
$tag_506_simple = "Access Category";
$tag_506 = "$tag_500_simple <span style='color:green;'>506</span>";
$tag_506_hint = "";
$tag_506_show = true;

//38summary 520
$tag_520_simple = "Summary";
$tag_520 = "$tag_520_simple <span style='color:green;'>520</span>";
$tag_520_show = true;
$tag_520_default_ind = "##";

//38se_pname 600
$tag_600_simple = "Subject Added Entry - Personal Name";
$tag_600 = "$tag_600_simple <span style='color:green;'>600</span>";
$tag_600_show = true;
$tag_600_default_ind = "";
    
//41subjectheading 650
$tag_650_simple = 'Subject Entry - Topical Term';
$tag_650 = "$tag_650_simple <span style='color:green;'>650</span>";
$tag_650_show = true;

//38pname 700 (max. 10)
$tag_700_simple = "Additional Authors";
$tag_700 = "$tag_700_simple <span style='color:green;'>700</span>";
$tag_700_show = true;
$tag_700_default_ind = "0#";

//38source 710
$tag_710_simple = "Corporate Name";
$tag_710 = "$tag_710_simple <span style='color:green;'>710</span>";
$tag_710_show = true;
$tag_710_a_default = "Perpustakaan Tuanku Bainun";
$tag_710_b_default = "Universiti Pendidikan Sultan Idris";
$tag_710_e_default = "Issuing body";
$tag_710_default_ind = "2#";

//38location 852
$tag_852_simple = "Location";
$tag_852 = "$tag_852_simple <span style='color:green;'>852</span>";
$tag_852_show = true;

//38link 856
$tag_856_simple = "HTTP Link";
$tag_856 = "$tag_856_simple <span style='color:green;'>856</span>";
$tag_856_show = true;

//enabled full text/abstract composer in add new / update
$default_is_abstract = true;//true|false
$enable_fulltext_abstract_composer = true;
$fulltext_abstract_composer_type = "richtext";//simpletext|richtext
$strip_tags_fulltext_abstract_composer = true;//remove html tags on display

//enabled reference composer in add new / update
$enable_reference_composer = true;
$reference_composer_type = "richtext";//simpletext|richtext
$strip_tags_reference_composer  = true;//remove html tags on display

//browser count generator. for subject, publisher and year browser
$item_count_generator = 'daily';//daily | live (live will be slower)

//report statistic generator count.
$report_count_generator = 'daily';//daily | live (live will be slower)

//OAI-PMH oai pmh policy statement for this repository
$enable_oai_pmh = true;//oai-pmh enabler. true = enabled OAI-PMH request, false = disable
$oai_rights = "closedAccess"; //openAccess | restrictedAccess | closedAccess | embargoedAccess
$oai_main_language = "eng"; //must be in ISO 639-3 format
$oai_main_format = "text";

//SEARCHER API
$enable_searcher_api = false;//enable or disable access to SEARCHER API

//FEEDBACK
$enable_feedback_function = false;//enable or disable feedback for items 

//DEPOSIT ENTRY
$enable_user_deposit_button = true;//true = show depositor button on index page; false = hide the button
$allow_depositor_function = true;//allow access to depositor page. will also provide admin to manage user deposit and depositor account management
$allow_confidential_setup = true;//allow user to set item confidentiality
$enable_self_activation = true;//allow user to self activate themselves via email links
    
    //for declaration
    $system_dfile_directory = "files/depos/d";//directory without the leading / and following / you have to make sure the directory is apache rewritable
    $system_allow_dfile_extension = "pdf";
    $system_allow_dfile_maxsize = "10";//in MB
    
    //for full text
    $system_pfile_directory = "files/depos/p";//directory without the leading / and following / you have to make sure the directory is apache rewritable
    $system_allow_pfile_extension = "pdf";
    $system_allow_pfile_maxsize = "100";//in MB
    
    //misc fields setting for user deposit
    $hide_main_author = true;
    $hide_additional_authors_entry = true;    

    $allow_declaration_submission = true;//allow submission of item declaration    
    $allow_abstract_submission = true;//allow abstract submission   
    $show_dateof_publication = false; //show (true) or hide (false) date of publication input    
    $limit_amount_userdeposit = 1;//set how many deposit can per user submit. to set unlimited, simply set 9999

    //this section controls the text and wording for depositor page
    //items with ^^<variable_name> are for sWADAH to replace them with values read from the system. if you ever changes this, value assignment will fail
    //all values are html codes compatible. use it wisely to preformat your output
    $depo_txt_identification = "Matrix Number";
    $depo_txt_slip_title = "DIGITAL DOCUMENT RECEIVING SLIP";
    $depo_txt_institution = "MY DIGITAL LIBRARY";
    $depo_txt_notvalidslip = "INVALID SLIP ID";
    $depo_txt_declaration_to_library = "Declaration of submission to the Library PDF.<br/>For CONFIDENTIAL or RESTRICTED item only.";
    $depo_txt_mandatory_fields = "Mandatory fields.<br/><span style='color:lightgrey;'><em>Medan mandatori.</em></span>";
    $depo_txt_optional_fields = "Optional. Might be required on for certain degree type. Kindly refer to your uploading criteria.<br/><span style='color:lightgrey;'><em>Medan pilihan. Mungkin diperlukan oleh beberapa jenis tahap pengajian. Sila rujuk kembali panduan memuatnaik bagi tahap pengajian anda.</em></span>";
    $depo_acknowledgement = "Acknowlegdement.<br/><span style='color:lightgrey;'><em>Pengesahan.</em></span>";
    $depo_image_institution = "sw_images/company_big-icon.png";
    $depo_para_acceptance_words = "
    Please be informed that your digital document with the title <br/><u><strong>^^titlestatement</strong></u> by <br/><u><strong>^^name</strong></u>
    with identification ID of  <u><strong>^^useridentity</strong></u><br/>
    has been accepted by us on <u><strong>^^approvedOn</strong></u>.
    ";
    $depo_validation = "<br/><div style='font-size:x-small;'>For validation purposes, scan this QR code::</div><br/>";
    $depo_para_autoremarks = "
    THIS SLIP IS AUTOMATICALLY GENERATED. <br/>NOT SIGNATURE IS REQUIRED.
    ";
    $depo_confidentiality_remarks = "
    <br/>If the thesis is CONFIDENTAL or RESTRICTED, please scan and attach with the letter from the organization with period and reasons for confidentiality or restriction.
    ";
    $depo_para_acknowledgement = "
    <h3 style='color:blue;'>Acknowledgement:</h3>
    I hereby acknowledged that MY DIGITAL LIBRARY reserves the right as follows:-</em><br/><br/>
    <ol>
    <li>The thesis is the property of MY DIGITAL LIBRARY.</li><br/>
    <li>The Library has the right to make copies for the purpose of reference and research.</li><br/>
    <li>The Library has the right to make copies of the thesis for academic exchange.</li><br/>
    </ol>
    ";

/*
xpdf module for displaying pdf pages number
*/
$usePdfInfo = false;//pdfinfo will show number of page on detail page. true | false. only set true when you have the pdfInfoCmd below on the right path
    $max_page_toshow_red_color = 30; //what minimum page number when the total page number will be shown with red color

/*
PHPMAILER Settings
you will need to set smtp information here
//Gmail SMTP setting: 
//For Gmail, less secure app access setting must be turn on. Google that for more info.
*/
    $useEmailNotification = false;//use email notification for certain feature ? true or false
    $emailDebuggerEnable = 0;//0 disable , 1 enable (when enable, redirection will turn off for email sending pages and allow you to see debugging messages)

    $emailMode = "ssl"; // ssl|tls|false
    $emailAuthentication = true; // true|false
    $emailAutoTLS = true; // true|false
    $emailHost = "smtp.gmail.com"; //host domain
    $emailPort = 465; // port number
    $emailUserName = "actual_sending_user@gmail.com"; // user name
    $emailPassword = "mypassword"; // password

    //email 'set from' to hide the real sender of email
    //use this to hide your email setting above
    $emailSetFrom = "aqualib@gmail";//this is usually an email address, but you can changed it to something else if you want your user to reply to specific email address
    $emailSetFromName = "sWADAH Mailer";
    $emailFooter = "<hr>This is an automated email. Please do not reply to this email. If you have any question regarding your submission, contact us at aqualib@gmail.com";

//set the default password to reset whenever reset password tool is used by admin for a user
$default_password_if_forgotten = "1";

//default creation password when creating new user
$default_create_password = "1";

//num of login attempt before blocking mechanism start
$default_num_attempt_login = 5;

//search debugging mode yes|no
$debug_search = "no";

//to show or not client ip on index page
$show_client_ip_on_startup = false;

?>