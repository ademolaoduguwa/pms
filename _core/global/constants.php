<?php

//JSON RESPONSE CONSTANTS
define('STATUS_OK', 1);
define('STATUS_ERROR', 2);
define('STATUS_ACCESS_DENIED', 3);
define('STATUS_NO_DATA', 4);

define('P_STATUS', 'status');
define('P_DATA', 'data');
define('P_MESSAGE', 'message');
define('P_ACCESS_TOKEN', 'access_token');

//ONLINE STATUS CODES
define('ONLINE', 1);
define('OFFLINE', 0);

//user type
define('PATIENT', 1);
define('STAFF', 2);
define('ADMIN', 3);

//staff roles
define('ROLES', 'roles');
define('EXISTING', 'existing');
define('AVAILABLE', 'available');
define('ADMINISTRATOR', 1);
define('DOCTOR', 2);
define('PHARMACIST', 3);
define('MEDICAL_RECORD', 4);
define('PERMISSION_GRANTER', 5);
define('URINE_CONDUCTOR', 6);
define('VISUAL_CONDUCTOR', 7);
define('XRAY_CONDUCTOR', 8);
define('HEALTH_SCHEME', 9);
define('PARASITOLOGY_CONDUCTOR', 10);
define('CHEMICAL_PATHOLOGY_CONDUCTOR', 11);
define('STAFF_ADDING_OFFICER', 12);
define('STAFF_CLEARANCE_OFFICER', 13);
define('TREATMENT_RECORD', 14);
define('ROASTER_CREATOR', 15);

//ACTIVE STATUS CONSTANTS
define('ACTIVE', 1);
define('INACTIVE', 2);
define('UNCLEAR', 3);
define('CLEARED', 4);
define('PENDING', 5);
define('PROCESSING', 6);
define('COMPLETED', 7);

//PATIENT ARRIVAL CONSTANTS
define('PARAMETER', 'parameter');
define('WILDCARD', 'wildcard');
define('QUEUE', 'queue');
define('LMT', 'LMT');
//PATIENT QUEUE FLAGS
define('QUEUE_EXIT', 0); //Patient has left queue
define('ON_DOCTOR_QUEUE', 1); //Patient is assigned to a doctor
define('ON_GENERAL_QUEUE', 2); //Patient is on general queue

//LONG POLLING CONSTANTS
define('POLLING_SLEEP_TIME', 5);
define('MAX_NUM_POLL', 4);

//GENERAL CONSTANTS
define('COUNT', 'count');
define('NAME', 'name');
define('VITALS', 'vitals');

//COMMUNICATION CONSTANTS
define('MAX_BODY_LENGTH', 200);
define('MSG_TYPE', 'msg_type');
define('INBOX_MESSAGE', 1);
define('SENT_MESSAGE', 2);
define('MAIL_PER_PAGE', 30);
define('START_INDEX', 'start_index');
define('END_INDEX', 'end_index');
define('TOTAL', 'total');
define('INBOX', 'inbox');
define('SENT', 'sent');
define('CURRENT_PAGE', 'current_page');
define('UNREAD', 1);
define('READ', 0);
define('UNREAD_MESSAGE', 'unread');
define('READ_MESSAGE', 'read');

//PRESCRIPTION STATUS
define('DRUG_UNCLEARED', 1);
define('DRUG_CLEARED', 2);
define('DRUG_UNAVAILABLE', 3);


//LABORATORY TEST TYPES
define('CHEMICAL_PATHOLOGY', 'chemical_pathology');
define('HAEMATOLOGY', 'haematology');
define('PARASITOLOGY', 'parasitology');
define('MICROSCOPY', 'microscopy');
define('VISUAL', 'visual');
define('RADIOLOGY', 'radiology');

//JQUERY AUTOCOMPLETE
define('TERM', 'term'); //jQuery Autocomplete request parameter

//BED CONSTANTS
define('OCCUPIED', 1);
define('VACANT', 0);