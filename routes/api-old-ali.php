<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::put('changePassword', 'ChangePasswordController@changePassword');

Route::group(['middleware' => ['jwt.verify']], function() {

    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

// ============ Department Management ================================================================================

Route::resource('department', 'DepartmentController');

//----------- activation department --------------
Route::get('/activationDepartment/{id}','DepartmentController@activationDepartment');
Route::get('/getActiveDepartment','DepartmentController@getActiveDepartment');
Route::get('/getDeactivateDepartment','DepartmentController@getDeactivateDepartment');

//============= Job Title Management =================================================================================

Route::resource('job', 'JobController');

//----------- activation job --------------
Route::get('/activationJob/{id}','JobController@activationJob');
Route::get('/getActiveJob','JobController@getActiveJob');
Route::get('/getDeactivateJob','JobController@getDeactivateJob');

//============== Labs Management ====================================================================================

Route::resource('lab', 'LabController');

//----------- activation lab --------------
Route::get('/activationLab/{id}','LabController@activationLab');
Route::get('/getActiveLab','LabController@getActiveLab');
Route::get('/getDeactivateLab','LabController@getDeactivateLab');

//============== Training Category =================================================================================

Route::resource('categories', 'CategoryController');
Route::get('getVendorInCategoryById/{id}','CategoryController@getVendorInCategoryById');

//----------- activation Category --------------
Route::get('/activationCategory/{id}','CategoryController@activationCategory');
Route::get('/getActiveCategory','CategoryController@getActiveCategory');
Route::get('/getDeactivateCategory','CategoryController@getDeactivateCategory');

//============= Vendor Management ==================================================================================

Route::resource('vendors', 'VendorController');

//----------- get courses in vendor --------------
Route::get('/coursesInVendor/{id}','VendorController@coursesInVendor');

//----------- get diplomas in vendor --------------
Route::get('/diplomasInVendor/{id}','VendorController@diplomasInVendor');

// ============= Courses Management ===============================================================================

Route::resource('courses', 'CourseController');
//----------- images course --------------
Route::put('/addImagesToCourse/{id}','CourseController@addImagesToCourse');
//----------- activation course --------------
Route::get('/activationCourse/{id}','CourseController@activationCourse');
Route::get('/getActiveCourse','CourseController@getActiveCourse');
Route::get('/getDeactivateCourse','CourseController@getDeactivateCourse');

// --------- Course prices --------------
Route::resource('coursePrices', 'CoursePricesController');
Route::get('/coursePrice/{id}','CoursePricesController@coursePrice');

// ============= Diploma Management ==============================================================================

Route::resource('diplomas', 'DiplomaController');

//----------- activation diploma --------------
Route::get('/activationDiplomas/{id}','DiplomaController@activationDiplomas');
Route::get('/getActiveDiplomas','DiplomaController@getActiveDiplomas');
Route::get('/getDeactivateDiplomas','DiplomaController@getDeactivateDiplomas');

//----------- add diploma to course --------------
Route::put('/addCoursesToDiploma/{id}','DiplomaController@addCoursesToDiploma');

//----------- detach diploma to course --------------
Route::put('/detachCoursesToDiploma/{id}','DiplomaController@detachCoursesToDiploma');

//----------- get courses by diploma id --------------
Route::get('/getCoursesByDiplomaId/{id}','DiplomaController@getCoursesByDiplomaId');

//----------- diploma prices --------------
Route::resource('diplomaPrices', 'DiplomaPricesController');
Route::get('/diplomaPrice/{id}','DiplomaPricesController@diplomaPrice');

// ============= Bank Management ==============================================================================

Route::resource('banks', 'BankController');

// ============= instructors Management ==============================================================================

Route::resource('instructors', 'InstructorController');

//----------- activation instructor --------------
Route::get('/activationInstructor/{id}','InstructorController@activationInstructor');
Route::get('/getActiveInstructor','InstructorController@getActiveInstructor');
Route::get('/getDeactivateInstructor','InstructorController@getDeactivateInstructor');

//----------- Create account instructor --------------
Route::put('/createAccountInstructor/{id}','InstructorController@createAccountInstructor');

//----------- course Track Instructor --------------
Route::get('/courseTrackInstructor/{id}','InstructorController@courseTrackInstructor');

//----------- diploma Track Instructor --------------
Route::get('/diplomaTrackInstructor/{id}','InstructorController@diplomaTrackInstructor');

//----------- lectures Instructor --------------
Route::get('/lecturesInstructor/{id}','InstructorController@lecturesInstructor');

//----------- latest Payments Instructor --------------
Route::get('/latestPaymentsInstructor/{id}','InstructorController@latestPaymentsInstructor');

//----------- upcoming Payments Instructor --------------
Route::get('/upcomingPaymentsInstructor/{id}','InstructorController@upcomingPaymentsInstructor');

//-----------  lectures Instructor today --------------
Route::get('/lecturesInstructorToday/{id}','InstructorController@lecturesInstructorToday');

//-----------  Student Instructor --------------
Route::get('/StudentInstructor/{id}','InstructorController@StudentInstructor');

// --------- training Categories --------------
Route::resource('trainingCategories', 'TrainingCategoryController');
Route::get('/trainingCategory/{id}','TrainingCategoryController@trainingCategory');

// --------- training Diplomas --------------
Route::resource('trainingDiplomas', 'TrainingDiplomaController');
Route::get('/trainingDiploma/{id}','TrainingDiplomaController@trainingDiploma');

// --------- training Courses --------------
Route::resource('trainingCourses', 'TrainingCourseController');
Route::get('/trainingCourse/{id}','TrainingCourseController@trainingCourse');

// ============= roles Management ==============================================================================

Route::resource('roles', 'RoleController');

// --------- all Permissions --------------
Route::get('/allPermissions','RoleController@allPermissions');

// ============= employees Management ==============================================================================

Route::resource('employees', 'EmployeeController');

//----------- activation employee --------------
Route::get('/activationEmployee/{id}','EmployeeController@activationEmployee');
Route::get('/getActiveEmployee','EmployeeController@getActiveEmployee');
Route::get('/getDeactivateEmployee','EmployeeController@getDeactivateEmployee');

//----------- Create account employee --------------
Route::put('/createAccountEmployee/{id}','EmployeeController@createAccountEmployee');

//----------- change Role --------------
Route::put('/changeRole','EmployeeController@changeRole');

// ============= Commission Management ==============================================================================

Route::resource('CommissionManagement', 'CommissionManagementController');

// --------- Sales Commission Plan Levels --------------
Route::resource('SalesCommissionPlan', 'CommissionPlanLevelsController');

// --------- Sales Commission Plan Levels --------------
Route::get('/getCommissionPlanLevels/{id}','CommissionPlanLevelsController@getCommissionPlanLevels');

// ============= Sales Team Targets ==============================================================================

Route::resource('salesTargets', 'SalesTargetController');

//----------- get sales employee --------------
Route::get('/salesEmployee','SalesTargetController@salesEmployee');

//----------- Sales Team Targets Details--------------
Route::resource('salesTargetDetails', 'SalesTargetDetailsController');

//----------- get Sales Team Targets Details --------------
Route::get('/salesTeamTargetsDetails/{id}','SalesTargetDetailsController@salesTeamTargetsDetails');

//----------- get Sales Target --------------
Route::get('/getSalesTarget','SalesTargetDetailsController@getSalesTarget');

// =================== Country ================================================================================

Route::get('/countries','CountryController@countries');
Route::get('/countriesWithCities','CountryController@countriesWithCities');
Route::get('/cities','CountryController@cities');
Route::get('/getCitiesByCountryId/{id}','CountryController@getCitiesByCountryId');

// ============= Interesting Levels ==============================================================================

Route::resource('interestingLevels', 'InterestingLevelController');

//----------- activation Interesting Levels --------------
Route::get('/activationInterestingLevel/{id}','InterestingLevelController@activationInterestingLevel');
Route::get('/getActiveInterestingLevel','InterestingLevelController@getActiveInterestingLevel');
Route::get('/getDeactivateInterestingLevel','InterestingLevelController@getDeactivateInterestingLevel');

// ============= lead Sources ==============================================================================

Route::resource('leadSources', 'LeadSourcesController');

//----------- activation lead Sources --------------
Route::get('/activationLeadSources/{id}','LeadSourcesController@activationLeadSources');
Route::get('/getActiveLeadSources','LeadSourcesController@getActiveLeadSources');
Route::get('/getDeactivateLeadSources','LeadSourcesController@getDeactivateLeadSources');

// ============= lead ==============================================================================

Route::resource('leads', 'LeadController');

//----------- Moving lead to another Employee --------------
Route::put('/movingLeadToAnotherEmployee/{id}','LeadController@movingLeadToAnotherEmployee');

//----------- add to list --------------
Route::post('/addList','LeadController@addList');

//----------- get 10 lead to employee --------------
Route::get('/getTenLead/{id}','LeadController@getTenLeadToEmployee');

//----------- get leads by employee id--------------
Route::get('/getLeadsEmployee/{id}','LeadController@getLeadsEmployee');

//----------- get leads by employee id to Register track --------------
Route::get('/getLeadsRegisterTrackEmployee/{id}','LeadController@getLeadsRegisterTrackEmployee');

//----------- get clients by employee id--------------
Route::get('/getClintEmployee/{id}','LeadController@getClintEmployee');

//----------- get clients--------------
Route::get('/getClint','LeadController@getClint');

// ================== Leads Followup ==================================================================

Route::resource('leadsFollowup', 'LeadsFollowupController');

//----------- activation Leads Followup --------------
Route::get('/activationLeadsFollowup/{id}','LeadsFollowupController@activationLeadsFollowup');
Route::get('/getActiveLeadsFollowup','LeadsFollowupController@getActiveLeadsFollowup');
Route::get('/getDeactivateLeadsFollowup','LeadsFollowupController@getDeactivateLeadsFollowup');

//----------- Reason --------------
Route::resource('reason', 'ReasonController');

//----------- get reason by leads followup id --------------
Route::get('/reasonsLeadsFollowup/{id}','ReasonController@reasonsLeadsFollowup');

//----------- activation Reason --------------
Route::get('/activationReason/{id}','ReasonController@activationReason');
Route::get('/getActiveReason','ReasonController@getActiveReason');
Route::get('/getDeactivateReason','ReasonController@getDeactivateReason');

//----------- lead activity  --------------
Route::resource('leadActivity', 'LeadActivityController');

//----------- Deal Individual Placement Test  --------------
Route::resource('dealIndividual', 'DealIndividualPlacementTestController');
Route::get('/getIndividualDeals','DealIndividualPlacementTestController@getIndividualDeals');

//----------- get Placement Test Deals By Employee Id --------------
Route::get('/getPlacementTestDealsByEmployeeId/{id}','DealIndividualPlacementTestController@getPlacementTestDealsByEmployeeId');

//----------- get lead by followup id and employee id --------------
Route::get('/getLeadFollowUpEmployee/{followup_id}/{employee_id}','LeadActivityController@getLeadFollowUpEmployee');

//----------- get lead interview by employee id --------------
Route::get('/getLeadInterviewEmployee/{id}','LeadActivityController@getLeadInterviewEmployee');

//----------- get lead courses by employee id --------------
Route::get('/getLeadCourseEmployee/{id}','LeadActivityController@getLeadCourseEmployee');

//----------- go Interview Sales --------------
Route::get('/goInterviewSales/{id}','LeadActivityController@goInterviewSales');

//----------- go Interview Sales --------------
Route::get('/goCourseSales/{id}','LeadActivityController@goCourseSales');

// ================== Company Management ============================================================

Route::resource('company', 'CompanyController');

//----------- Company contacts  --------------
Route::resource('companyContact', 'CompanyContactController');

//----------- get company contact by company id --------------
Route::get('/companyContactByCompanyId/{id}','CompanyContactController@companyContactByCompanyId');

//----------- Company Leads  --------------
Route::resource('companyLeads', 'CompanyLeadController');

//----------- get company leads by company id --------------
Route::get('/companyLeadsByCompanyId/{id}','CompanyLeadController@companyLeadsByCompanyId');

//----------- add Company Lead new --------------
Route::post('/companyLeadsNew','CompanyLeadController@companyLeadsNew');

//----------- Moving companies to another Employee --------------
Route::put('/movingCompanyToAnotherEmployee/{id}','CompanyController@movingCompanyToAnotherEmployee');

//----------- add to list --------------
Route::post('/addListCompany','CompanyController@addListCompany');

//----------- get 10 companies to employee --------------
Route::get('/getTenCompanyToEmployee/{id}','CompanyController@getTenCompanyToEmployee');

//----------- get companies by employee id--------------
Route::get('/getCompaniesEmployee/{id}','CompanyController@getCompaniesEmployee');

//----------- company activity  --------------
Route::resource('companyActivity', 'CompanyActivityController');

//----------- get company by followup id and employee id --------------
Route::get('/getCompanyFollowUpEmployee/{followup_id}/{employee_id}','CompanyActivityController@getCompanyFollowUpEmployee');

//----------- get lead interview by employee id and company id --------------
Route::get('/getLeadInterviewByEmployeeIdAndCompanyId/{employee_id}/{company_id}','CompanyActivityController@getLeadInterviewByEmployeeIdAndCompanyId');

//----------- get lead courses by employee id and company id --------------
Route::get('/getLeadCourseByEmployeeIdAndCompanyId/{employee_id}/{company_id}','CompanyActivityController@getLeadCourseByEmployeeIdAndCompanyId');

//----------- add deal placement test to lead in company --------------
Route::post('/dealCompanyPlacementTest','DealCompanyPlacementTestController@store');

//----------- get Deal Placement Test Leads By Company Id --------------
Route::get('/getDealPlacementTestCompany/{id}','DealCompanyPlacementTestController@getDealPlacementTestCompany');

//----------- add deal interview to lead in company --------------
Route::post('/dealCompanyInterview','DealCompanyInterviewController@store');

//----------- get Deal interview Leads By Company Id --------------
Route::get('/getDealInterviewCompany/{id}','DealCompanyInterviewController@getDealInterviewCompany');

// ================== Companies Followup ==================================================================

Route::resource('companiesFollowup', 'CompanyFollowupController');

//----------- activation companies Followup --------------
Route::get('/activationCompanyFollowup/{id}','CompanyFollowupController@activationCompanyFollowup');
Route::get('/getActiveCompanyFollowup','CompanyFollowupController@getActiveCompanyFollowup');
Route::get('/getDeactivateCompaniesFollowup','CompanyFollowupController@getDeactivateCompaniesFollowup');

//----------- Company Reason --------------
Route::resource('companyReason', 'CompanyFollowupReasonController');

//----------- get reason by companies followup id --------------
Route::get('/reasonsCompaniesFollowup/{id}','CompanyFollowupReasonController@reasonsCompaniesFollowup');

//----------- activation Company Reason --------------
Route::get('/activationCompanyReason/{id}','CompanyFollowupReasonController@activationCompanyReason');
Route::get('/getActiveCompanyReason','CompanyFollowupReasonController@getActiveCompanyReason');
Route::get('/getDeactivateCompanyReason','CompanyFollowupReasonController@getDeactivateCompanyReason');

// ============= exam Types Management ==============================================================================

Route::resource('examTypes', 'ExamTypesController');

//----------- activation employee --------------
Route::get('/activationExamType/{id}','ExamTypesController@activationExamType');
Route::get('/getActiveExamType','ExamTypesController@getActiveExamType');
Route::get('/getDeactivateExamType','ExamTypesController@getDeactivateExamType');

// ============= exam Management ==============================================================================

Route::resource('exam', 'ExamController');
//----------- get placement test --------------
Route::get('/getPlacementTest','ExamController@getPlacementTest');
//----------- get quiz --------------
Route::get('/getQuiz','ExamController@getQuiz');
//----------- get final exam --------------
Route::get('/getFinalExam','ExamController@getFinalExam');

// ============= exam degree Management ==============================================================================

Route::resource('examDegrees', 'ExamDegreeController');

// ============= part Management ==============================================================================

Route::resource('part', 'PartController');

//----------- get part by exam id --------------
Route::get('/examParts/{id}','PartController@examParts');

//----------- question Type --------------
Route::resource('questionType', 'QuestionTypeController');

//----------- main question --------------
Route::resource('mainQuestion', 'MainQuestionController');

//----------- question --------------
Route::resource('question', 'QuestionController');
//----------- get question by main question id --------------
Route::get('/getQuestionByMainQuestionId/{id}','QuestionController@getQuestionByMainQuestionId');
//----------- get question by exam id --------------
Route::get('/getQuestionByExamId/{id}','QuestionController@getQuestionByExamId');
//----------- get question by part id and type id --------------
Route::get('/getQuestionByPartIdTypeId/{part_id}/{type_id}','QuestionController@getQuestionByPartIdTypeId');

// ============= lead exams ==============================================================================

Route::resource('leadExams', 'LeadTestController');

//----------- login leads to placement test --------------
Route::post('/loginLead','LeadTestController@loginLead');

//----------- get lead courses by employee id --------------
Route::get('/reExamByLeadIdAndExamId/{lead_id}/{exam_id}','LeadTestController@reExamByLeadIdAndExamId');

//----------- get question by lead id and exam id --------------
Route::get('/getQuestionByLeadIdAndExamId/{lead_id}/{exam_id}','LeadTestController@getQuestionByLeadIdAndExamId');

//----------- lead certificate --------------
Route::post('certificate', 'CertificateController@store');

//----------- get certificate by lead id and exam id --------------
Route::get('getCertificateByLeadIdAndExamId/{lead_id}/{exam_id}', 'CertificateController@getCertificateByLeadIdAndExamId');

//----------- get lead answer by lead id and exam id --------------
Route::get('getLeadAnswerByLeadIdAndExamId/{lead_id}/{exam_id}', 'CertificateController@getLeadAnswerByLeadIdAndExamId');

//----------- get certificate placement test by lead id --------------
Route::get('getCertificatePlacementTestByLeadId/{lead_id}', 'CertificateController@getCertificatePlacementTestByLeadId');

// ============= Interview Type Management ==============================================================================

Route::resource('interviewType', 'InterviewTypeController');

//----------- activation Interview Type --------------
Route::get('/activationInterviewType/{id}','InterviewTypeController@activationInterviewType');
Route::get('/getActiveInterviewType','InterviewTypeController@getActiveInterviewType');
Route::get('/getDeactivateInterviewType','InterviewTypeController@getDeactivateInterviewType');

//================================= Deal Individual interview  ========================================

Route::resource('dealIndividualInterview', 'DealInterviewController');

//----------- get individual deals interview --------------
Route::get('/getIndividualDealInterview','DealInterviewController@getIndividualDealInterview');

//----------- get Deal Interview By Employee Id --------------
Route::get('/getDealInterviewByEmployeeId/{id}','DealInterviewController@getDealInterviewByEmployeeId');

//================================= interview Management  ========================================
Route::resource('interview', 'InterviewController');

//----------- get interview by lead id --------------
Route::get('/getInterviewByLeadId/{id}','InterviewController@getInterviewByLeadId');

//----------- get interview by instructor id --------------
Route::get('/getInterviewByInstructorId/{id}','InterviewController@getInterviewByInstructorId');

//----------- interview result management --------------
Route::resource('interviewResult', 'InterviewResultController');

//----------- get Interview Result By Lead Id --------------
Route::get('/getInterviewResultByLeadId/{id}','InterviewResultController@getInterviewResultByLeadId');

//================================= Deal Individual selta  ========================================

Route::resource('dealIndividualSelta', 'DealSeltaController');

//----------- get individual deals selta --------------
Route::get('/getIndividualDealSelta','DealSeltaController@getIndividualDealSelta');

//----------- get  deals selta By Employee Id --------------
Route::get('/getDealSeltaByEmployeeId/{id}','DealSeltaController@getDealSeltaByEmployeeId');

//================================= selta Management  ========================================
Route::resource('selta', 'SeltaController');

//----------- get selta by lead id --------------
Route::get('/getSeltaByLeadId/{id}','SeltaController@getSeltaByLeadId');

//----------- get selta by instructor id --------------
Route::get('/getSeltaByInstructorId/{id}','SeltaController@getSeltaByInstructorId');

//----------- interview result management --------------
Route::resource('seltaResult', 'SeltaResultController');

//----------- get selta Result By Lead Id --------------
Route::get('/getSeltaResultByLeadId/{id}','SeltaResultController@getSeltaResultByLeadId');

//============== discount Management =================================================================================

Route::resource('discount', 'DiscountController');

//----------- get public discount --------------
Route::get('getPublicDiscount','DiscountController@getPublicDiscount');

//----------- get special discount --------------
Route::get('getSpecialDiscount','DiscountController@getSpecialDiscount');

//----------- activation discount --------------
Route::get('/activationDiscount/{id}','DiscountController@activationDiscount');
Route::get('/getActiveDiscount','DiscountController@getActiveDiscount');
Route::get('/getDeactivateDiscount','DiscountController@getDeactivateDiscount');

//================================= course track  ========================================

Route::resource('courseTrack', 'CourseTrackController');

//----------- check Schedule --------------
Route::post('/checkSchedule','CourseTrackController@checkSchedule');

//----------- check course track Schedule By course track id --------------
Route::post('/checkTrackSchedule/{id}','CourseTrackController@checkTrackSchedule');

//----------- cancel course track --------------
Route::get('/cancel/{id}','CourseTrackController@cancel');

//----------- get drop down course track by vendor id --------------
Route::get('/DropdownsCourseTrack/{id}','CourseTrackController@DropdownsCourseTrack');

//----------- get course track by lead id --------------
Route::get('/getCourseTrackByLeadId/{id}','CourseTrackController@getCourseTrackByLeadId');

//----------- course track schedule --------------
Route::resource('schedule', 'CourseTrackScheduleController');

//----------- get schedule by course track id --------------
Route::get('/getScheduleByCourseTrackId/{id}','CourseTrackScheduleController@getScheduleByCourseTrackId');

//----------- get all schedule instructor (by instructor id) --------------
Route::get('/instructorSchedule/{id}','CourseTrackScheduleController@instructorSchedule');

//----------- search lectures course track by date --------------
Route::post('/searchLecturesCourse','CourseTrackScheduleController@searchLecturesCourse');

//----------- chang course track price by course track id --------------
Route::post('/updateCourseTrackPrice/{id}','CourseTrackController@updateCourseTrackPrice');

//----------- public discount --------------
Route::resource('publicDiscount', 'PublicDiscountController');

//----------- Course Track Student --------------
Route::resource('courseTrackStudent', 'CourseTrackStudentController');

//----------- get register course track by employee id and course track id --------------
Route::get('/registerCourseTrackByEmployeeIdAndCourseTrackId/{employee_id}/{course_track_id}','CourseTrackStudentController@registerCourseTrackByEmployeeIdAndCourseTrackId');

//----------- get attendance course track by course track id --------------
Route::get('/studentAttendanceCourseTrack/{course_track_id}','CourseTrackStudentController@studentAttendanceCourseTrack');


//----------- Transfer To another Salesman --------------
Route::put('/TransferToAnotherSalesman','CourseTrackStudentController@TransferToAnotherSalesman');

//----------- comment course track --------------
Route::resource('courseTrackComment', 'CourseTrackStudentCommentController');

//----------- course track student payment --------------
Route::resource('courseTrackPayment', 'CourseTrackStudentPaymentController');

//----------- course track student cancel --------------
Route::resource('cancelBooking', 'CourseTrackStudentCancelController');

//----------- course track student waiting --------------
Route::resource('CourseTrackWaiting', 'CourseTrackStudentWaitingController');

//----------- get register course track by employee id --------------
Route::get('/WaitingCourseTrackStudentByEmployeeId/{id}','CourseTrackStudentWaitingController@WaitingCourseTrackStudentByEmployeeId');

//----------- Transfer To Waiting List --------------
Route::put('/TransferToWaitingList/{id}','CourseTrackStudentWaitingController@TransferToWaitingList');


//================================= diploma track  ========================================

Route::resource('diplomaTrack', 'DiplomaTrackController');

//----------- check Schedule diploma --------------
Route::post('/checkScheduleDiploma','DiplomaTrackController@checkSchedule');

//----------- check diploma track Schedule By diploma track id to update diploma track Schedule --------------
Route::post('/checkDiplomaTrackSchedule/{id}','DiplomaTrackController@checkDiplomaTrackSchedule');

//----------- cancel diploma track --------------
Route::get('/cancelDiplomaTrack/{id}','DiplomaTrackController@cancel');

//----------- chang diploma track price by diploma track id --------------
Route::post('/updateDiplomaTrackPrice/{id}','DiplomaTrackController@updateDiplomaTrackPrice');

//----------- get drop down diploma track by vendor id --------------
Route::get('/DropdownsDiplomaTrack/{id}','DiplomaTrackController@DropdownsDiplomaTrack');

//----------- get drop down course by diploma track id --------------
Route::get('/DropdownsCourseDiplomaTrack/{id}','DiplomaTrackController@DropdownsCourseDiplomaTrack');

//----------- get diploma track by lead id --------------
Route::get('/getDiplomaTrackByLeadId/{id}','DiplomaTrackController@getDiplomaTrackByLeadId');

//----------- diploma track schedule --------------
Route::resource('diplomaSchedule', 'DiplomaTrackScheduleController');

//----------- get schedule by diploma track id --------------
Route::get('/getScheduleByDiplomaTrackId/{id}','DiplomaTrackScheduleController@getScheduleByDiplomaTrackId');

//----------- get all schedule instructor (by instructor id) --------------
Route::get('/instructorScheduleDiploma/{id}','DiplomaTrackScheduleController@instructorScheduleDiploma');

//----------- public discount diploma track --------------
Route::resource('publicDiscountDiploma', 'PublicDiscountDiplomaController');

//----------- diploma Track Student --------------
Route::resource('diplomaTrackStudent', 'DiplomaTrackStudentController');

//----------- get register course track by employee id and course track id --------------
Route::get('/registerDiplomaTrackByEmployeeIdAndDiplomaTrackId/{employee_id}/{course_track_id}','DiplomaTrackStudentController@registerDiplomaTrackByEmployeeIdAndDiplomaTrackId');

//----------- search lectures diploma track by date --------------
Route::post('/searchLecturesDiploma','DiplomaTrackScheduleController@searchLecturesDiploma');

//----------- get attendance course track by course track id --------------
Route::get('/studentAttendanceDiplomaTrack/{diploma_track_id}','DiplomaTrackStudentController@studentAttendanceDiplomaTrack');

//----------- Transfer To another Salesman --------------
Route::put('/TransferToAnotherSalesmanDiploma','DiplomaTrackStudentController@TransferToAnotherSalesmanDiploma');

//----------- comment course track --------------
Route::resource('diplomaTrackComment', 'DiplomaTrackStudentCommentController');

//----------- diploma track student payment --------------
Route::resource('diplomaTrackPayment', 'DiplomaTrackStudentPaymentController');

//----------- diploma track student cancel --------------
Route::resource('cancelBookingDiploma', 'DiplomaTrackStudentCancelController');

//----------- diploma track student waiting --------------
Route::resource('DiplomaTrackWaiting', 'DiplomaTrackStudentWaitingController');

//----------- get register diploma track by employee id  --------------
Route::get('/WaitingDiplomaTrackStudentByEmployeeId/{id}','DiplomaTrackStudentWaitingController@WaitingDiplomaTrackStudentByEmployeeId');

//----------- Transfer To Waiting List diploma track --------------
Route::put('/TransferToWaitingListDiploma/{id}','DiplomaTrackStudentWaitingController@TransferToWaitingListDiploma');

//----------- instructor Attendance --------------
Route::resource('instructorAttendance', 'InstructorAttendanceController');

//----------- trainees Attendance Course --------------
Route::resource('traineesAttendanceCourse', 'TraineesAttendanceCourseController');

//----------- trainees Attendance diploma --------------
Route::resource('traineesAttendanceDiploma', 'TraineesAttendanceDiplomaController');

//----------- black list student --------------
Route::resource('blackList', 'BlackListController');

//----------- activation student --------------
Route::get('/activationStudent/{id}','ClientController@activationStudent');
Route::get('/getActiveStudent','ClientController@getActiveStudent');
Route::get('/getDeactivateStudent','ClientController@getDeactivateStudent');

//----------- Create account student --------------
Route::put('/createAccountStudent/{id}','ClientController@createAccountStudent');

//----------- get student details by id --------------
Route::get('/clientDetails/{id}','ClientController@clientDetails');

//----------- get student payment details --------------
Route::post('/clientPaymentDitails','ClientController@clientPaymentDitails');

//----------- accountant payment student --------------
Route::post('/AccountantPaymentStudent/{id}','Accountantcontroller@store');

//----------- get student payment details --------------
Route::post('/refundPayment/{id}','Accountantcontroller@refundPayment');

//----------- get lead accountant details --------------
Route::get('/getAccountantLead','Accountantcontroller@getAccountantLead');

//----------- create accountant payment lead by lead id --------------
Route::post('/AccountantPaymentLead/{id}','Accountantcontroller@AccountantPaymentLead');

// ================== Income Management ============================================================

Route::resource('income', 'IncomeController');

//----------- get all Income --------------
Route::get('/allIncome','IncomeController@allIncome');

//----------- get main Income --------------
Route::get('/mainIncome','IncomeController@mainIncome');

//----------- activation Income --------------
Route::get('/activationIncome/{id}','IncomeController@activationIncome');
Route::get('/getActiveIncome','IncomeController@getActiveIncome');
Route::get('/getDeactivateIncome','IncomeController@getDeactivateIncome');

// ================== Expense Management ============================================================

Route::resource('expense', 'ExpenseController');

//----------- get all Expense --------------
Route::get('/allExpense','ExpenseController@allExpense');

//----------- get main Expense --------------
Route::get('/mainExpense','ExpenseController@mainExpense');

//----------- activation Expense --------------
Route::get('/activationExpense/{id}','ExpenseController@activationExpense');
Route::get('/getActiveExpense','ExpenseController@getActiveExpense');
Route::get('/getDeactivateExpense','ExpenseController@getDeactivateExpense');

// ================== income And Expense Management ============================================================

Route::resource('incomeAndExpense', 'IncomeAndExpenseController');

// ================== Treasury Management ============================================================

Route::resource('treasury', 'TreasuryController');

//----------- get main treasury --------------
Route::get('/mainTreasury','TreasuryController@mainTreasury');

//----------- activation treasury --------------
Route::get('/activationTreasury/{id}','TreasuryController@activationTreasury');
Route::get('/getActiveTreasury','TreasuryController@getActiveTreasury');
Route::get('/getDeactivateTreasury','TreasuryController@getDeactivateTreasury');

// ================== Instructor Payments ============================================================

Route::resource('instructorPayment', 'InstructorPaymentController');

// ================== Sales Team Payment ============================================================

Route::resource('salesTeamPayment', 'SalesTeamPaymentController');

//----------- Sales Team Payment details by target employee id and employee id --------------
Route::get('/salesTeamPaymentDetails/{id}/{employee_id}','SalesTeamPaymentController@salesTeamPaymentDetails');

// ================== Treasury Transaction Out ============================================================

Route::resource('treasuryTransactionOut', 'TreasuryTransactionOutController');

// ================== Treasury Transaction in ============================================================

Route::resource('treasuryTransactionIn', 'TreasuryTransactionInController');

// ================== Transferring Treasury Payment ============================================================

Route::resource('transferringTreasury', 'TransferringTreasuryController');

// ================== Transferring Treasury Payment ============================================================

Route::get('/userProfile/{id}/{type}','ProfileController@userProfile');
Route::put('/profileImg/{id}','ProfileController@profileImg');
Route::put('/profileData/{id}','ProfileController@profileData');
Route::put('/changePasswordUser/{id}','ProfileController@changePasswordUser');

// ================== Account Report ============================================================

//----------- profit Report treasury --------------
Route::post('/profitReport','AccountReportController@profitReport');

//----------- expense Report treasury --------------
Route::post('/expenseReport','AccountReportController@expenseReport');

//----------- expense Request Report --------------
Route::post('/expenseRequestReport','AccountReportController@expenseRequestReport');

//----------- income Report treasury --------------
Route::post('/incomeReportTreasury','AccountReportController@incomeReportTreasury');

//----------- income request Report --------------
Route::post('/incomeReport','AccountReportController@incomeReport');

//----------- daily balance report --------------
Route::post('/dailyBalanceReport','AccountReportController@dailyBalanceReport');

//----------- treasury Balance Report --------------
Route::post('/treasuryBalanceReport','AccountReportController@treasuryBalanceReport');

//----------- trainee Payment Request Report --------------
Route::post('/traineePaymentRequestReport','AccountReportController@traineePaymentRequestReport');

//----------- Treasury Diploma Collections Report --------------
Route::post('/treasuryDiplomaCollectionsReport','AccountReportController@treasuryDiplomaCollectionsReport');

//----------- Treasury Course Collections Report --------------
Route::post('/treasuryCourseCollectionsReport','AccountReportController@treasuryCourseCollectionsReport');

// ================== Instructor Report ============================================================

//----------- Instructors Payments Report --------------
Route::post('/instructorsPaymentsReport','InstructorReportController@instructorsPaymentsReport');

//----------- Instructor Latest Payments Report --------------
Route::post('/instructorLatestPaymentsReport','InstructorReportController@instructorLatestPaymentsReport');

//----------- Instructor Lectures Course --------------
Route::post('/InstructorLecturesCourseReport','InstructorReportController@InstructorLecturesCourseReport');

//----------- Instructor Lectures diploma --------------
Route::post('/InstructorLecturesDiplomaReport','InstructorReportController@InstructorLecturesDiplomaReport');

//----------- Instructor Lectures --------------
Route::post('/InstructorLecturesReport','InstructorReportController@InstructorLecturesReport');

//----------- Instructor Attendance Report --------------
Route::post('/instructorsAttendanceReport','InstructorReportController@instructorsAttendanceReport');

// ================== student Report ============================================================

//----------- knowing Us Methods Report --------------
Route::post('/knowingUsMethodsReport','StudentReportController@knowingUsMethodsReport');

//----------- Interesting Levels Report --------------
Route::post('/interestingLevelsReport','StudentReportController@interestingLevelsReport');

//----------- student Attendance Course Percentage --------------
Route::post('/studentAttendanceCoursePercentage','StudentReportController@studentAttendanceCoursePercentage');

//----------- Student Lecture Report --------------
Route::post('/StudentLectureReport','StudentReportController@StudentLectureReport');

// ================== student Report ============================================================

//----------- Sales Team Client Report --------------
Route::post('/SalesTeamClientReport','SalesReportController@SalesTeamClientReport');

//----------- Sales Team Clients number Report --------------
Route::post('/SalesTeamClientNumberReport','SalesReportController@SalesTeamClientNumberReport');

//----------- Sales Team Targets Report --------------
Route::post('/SalesTeamTargetsReport','SalesReportController@SalesTeamTargetsReport');

//----------- Sales Team Target Details Report --------------
Route::post('/SalesTeamTargetDetailsReport','SalesReportController@SalesTeamTargetDetailsReport');

//----------- Total Sales Team Targets Report --------------
Route::post('/TotalSalesTeamTargetsReport','SalesReportController@TotalSalesTeamTargetsReport');

//----------- Sales Target History Report --------------
Route::post('/SalesTargetHistoryReport','SalesReportController@SalesTargetHistoryReport');

//----------- diploma Track Count Student Report --------------
Route::post('/diplomaTrackCountStudentReport','SalesReportController@diplomaTrackCountStudentReport');

//----------- course Track Count Student Report --------------
Route::post('/courseTrackCountStudentReport','SalesReportController@courseTrackCountStudentReport');

//----------- Sales Salary Report --------------
Route::post('/SalesSalaryReport','SalesReportController@SalesSalaryReport');

//----------- Training Lab Occupancy Rate Report --------------
Route::post('/TrainingLabOccupancyRateReport','SalesReportController@TrainingLabOccupancyRateReport');

//----------- money Back Report --------------
Route::post('/moneyBackReport','SalesReportController@moneyBackReport');

// ================== Student Comment ============================================================

Route::resource('studentComment', 'StudentCommentController');
