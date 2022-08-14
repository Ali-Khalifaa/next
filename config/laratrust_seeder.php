<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [

        'super_admin' => [
//
//            'banks' => 'c,r,u,d',
//
//            'categories' => 'c,r,u,d,a',
//
//            'courses' => 'c,r,u,d,a',
//
//            'coursePrices' => 'c,r,u,d',
//
//            'departments' => 'c,r,u,d,a',
//
//            'diplomas' => 'c,r,u,d,a',
//
//            'diplomaPrices' => 'c,r,u,d',
//
//            'instructors' => 'c,r,u,d,a,cA,cP',
//
//            'jobs' => 'c,r,u,d,a',
//
//            'labs' => 'c,r,u,d,a',
//
//            'roles' => 'c,r,u',
//
//            'trainingCategories' => 'c,r,u,d',
//
//            'trainingCourses' => 'c,r,u,d',
//
//            'trainingDiplomas' => 'c,r,u,d',
//
//            'vendors' => 'c,r,u,d',
//
//            'employees' => 'c,r,u,d,a,cA,cP,cR',
//
//            'commissionManagement' => 'c,r,u,d',
//
//            'salesCommissionPlan' => 'c,r,u,d',
//
//            'salesTarget' => 'c,r,u,d',
//
//            'targetEmployees' => 'c,r,u,d',
//
//            'interestingLevel' => 'c,r,u,d,a',
//
//            'leadSources' => 'c,r,u,d,a',
//
//            'lead' => 'c,r,u,aL',
//
//            'leadsFollowup' => 'c,r,u,d,a',
//
//            'reasons' => 'c,r,u,d,a',
//
//            'leadActivity' => 'c,r',
//
//            'DealIndividualPlacementTest' => 'c',
//
//            'companies' => 'c,r,u,d',
//
//            'companyContacts' => 'c,r,u,d',
//
//            'companyLeads' => 'c,r,u,d',
//
//            'companyFollowup' => 'c,r,u,d,a',
//
//            'companyReasons' => 'c,r,u,d,a',
//
//            'companyActivity' => 'c,r',
//
//            'DealCompanyPlacementTest' => 'c',
//
//            'companyDeals' => 'c,r,u,d',
//
//            'examTypes' => 'c,r,u,d,a',
//
//            'exam' => 'c,r,u,d',
//
////            'examDegrees' => 'c,r,u,d',
//
//            'parts' => 'c,r,u,d',
//
//            'questionType' => 'r',
//
//            'mainQuestion' => 'c,r,u,d',
//
//            'question' => 'c,r,u,d',
//
//            'LeadTest' => 'c,r,u,d',

        ],
        'admission_manager' => [],
        'sales_manager' => [],
        'sales' => [],
        'accountant_manager' => [],
        'admission' => [],
        'coordinator' => [],
        'accountant' => [],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
        'a' => 'activation',
        'cA' => 'create_account',
        'cP' => 'change_password',
        'cR' => 'change_role',
        'aL' => 'add_list',
    ]
];
