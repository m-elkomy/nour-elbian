<?php


function lang($phrase)
{
    static $lang = array( // static array for not relocating array every time
        'login' => 'login',
        'Admin Login' => 'Admin Login',
        'UserName' => 'UserName',
        'Password' => 'Password',
        'Login' => 'Login',
        'Dashboard' => 'Dashboard',
        'Total Member'=> 'Total Member',
        'Total Teachers'=> 'Total Teachers',
        'Total Students'=> 'Total Students',
        'Total Classes'=> 'Total Classes',
        'Add Branch'=> 'Add Branch',
        'Registerd Users'=> 'Registerd Users',
        'Latest'=> 'Latest',
        'Sorry There Is No Recored To Show'=> 'Sorry There Is No Recored To Show',
        'Registerd Teachers'=> 'Registerd Teachers',
        'Registerd Student'=> 'Registerd Student',
        'Registerd Classes'=> 'Registerd Classes',
        'Edit'=> 'Edit',
        'Nour-ElBian'=> 'Nour-ElBian',
        'Branches'=> 'Branches',
        'Teachers'=> 'Teachers',
        'Students'=> 'Students',
        'Classes'=> 'Classes',
        'View'=> 'View',
        'Users'=> 'Users',
        'Manage Branch'=> 'Manage Branch',
        'Branch ID'=> 'Branch ID',
        'Branch Name'=> 'Branch Name',
        'Control'=> 'Control',
        'Delete'=> 'Delete',
        'Add New Branch'=> 'Add New Branch',
        'Edit Branch'=> 'Edit Branch',
        'Update Branch'=> 'Update Branch',
        'You Can Not Leave This Field Empty'=> 'You Can Not Leave This Field Empty',
        'Sorry You Can Not Browse This Page Directly'=> 'Sorry You Can Not Browse This Page Directly',
        'Insert Branch'=> 'Insert Branch',
        'Sorry This Name Is Exist'=> 'Sorry This Name Is Exist',
        'Row Inserted'=> 'Row Inserted',
        'Sorry You Can Not Leave Branch Name Empty'=> 'Sorry You Can Not Leave Branch Name Empty',
        'Delete Branch'=> 'Delete Branch',
        'Row Deleted'=> 'Row Deleted',
        'Sorry There Is No Such ID'=> 'Sorry There Is No Such ID',
        'Update'=> 'Update',
        'Row Update'=> 'Row Update',
        'Save'=> 'Save',
        'Sorry Branch Name Can Not Be Less Than 4 Character'=> 'Sorry Branch Name Can Not Be Less Than 4 Character',
        'Manage Teachers'=> 'Manage Teachers',
        'Teacher ID'=> 'Teacher ID',
        'Teacher Name'=> 'Teacher Name',
        'Add New Teahcer'=> 'Add New Teahcer',
        'Edit Teacher'=> 'Edit Teacher',
        'Edit Teacher'=> 'Edit Teacher',
        'Update Teacher'=> 'Update Teacher',
        'Teacher Name Can Not Be Empty'=> 'Teacher Name Can Not Be Empty',
        'Branch Name Can Not Be Empty'=> 'Branch Name Can Not Be Empty',
        'Add Teacher'=> 'Add Teacher',
        'Insert Teacher'=> 'Insert Teacher',
        'Sorry This Teacher Name is Exist'=> 'Sorry This Teacher Name is Exist',
        'Delete Teacher'=> 'Delete Teacher',
        'Teacher Name Can Not Be Less Than 4 Character'=> 'Teacher Name Can Not Be Less Than 4 Character',
        'Manage Student'=> 'Manage Student',
        'Manage Salary'=> 'Manage Salary',
        'Student'=> 'Student',
        'Classes'=> 'Classes',
        'Branch'=> 'Branch',
        'Teachers'=> 'Teachers',
        'Arabic'=> 'Arabic',
        'Englishُ'=> 'Englishُ',
        'Student ID'=> 'Student ID',
        'Student Name'=> 'Student Name',
        'Class Name'=> 'Class Name',
        'Pay Or Not'=> 'Pay Or Not',
        'Add New Student'=> 'Add New Student',
        'Edit Student'=> 'Edit Student',
        'Paid'=> 'Paid',
        'Update Student'=> 'Update Student',
        'Student Name Can Not Be Empty'=> 'Student Name Can Not Be Empty',
        'Class Name Can Not Be Empty'=> 'Class Name Can Not Be Empty',
        'Add Students'=> 'Add Students',
        'Insert Student'=> 'Insert Student',
        'Sort By'=> 'Sort By',
        'Yes'=> 'Yes',
        'No'=> 'No',
        'Student Name Can Not Be Less Than 4 Character'=> 'Student Name Can Not Be Less Than 4 Character',
        'Delete Student'=> 'Delete Student',
        'Manage Classes'=> 'Manage Classes',
        'Class ID'=> 'Class ID',
        'Add New Class'=> 'Add New Class',
        'Edit Class'=> 'Edit Class',
        'Update Class'=> 'Update Class',
        'Class Name Can Not Be Empty'=> 'Class Name Can Not Be Empty',
        'Add Class'=> 'Add Class',
        'Insert Class'=> 'Insert Class',
        'Sorry This Class Name Is Exist'=> 'Sorry This Class Name Is Exist',
        'Delete Class'=> 'Delete Class',
        'View Branch'=> 'View Branch',
        'View Branch'=> 'View Branch',
        'Branches'=> 'Branches',
        'Class'=> 'Class',
        'Create PDF'=> 'Create PDF',
        'View Classes'=> 'View Classes',
        'Classes'=> 'Classes',
        'View Teachers'=> 'View Teachers',
        'View Students'=> 'View Students',
        'Manage Members'=> 'Manage Members',
        'User ID'=> 'User ID',
        'User Name'=> 'User Name',
        'Full Name'=> 'Full Name',
        'Email'=> 'Email',
        'Registered Date'=> 'Registered Date',
        'Add New Member'=> 'Add New Member',
        'Edit Member'=> 'Edit Member',
        'Password'=> 'Password',
        'Update Member'=> 'Update Member',
        'UserName Can Not Be Empty'=> 'UserName Can Not Be Empty',
        'User Name Can Not Be Less Than 4 Characters'=> 'User Name Can Not Be Less Than 4 Characters',
        'User Name Can Not Be Greater Than 20 Characters'=> 'User Name Can Not Be Greater Than 20 Characters',
        'Full Name Can Not Be Empty'=> 'Full Name Can Not Be Empty',
        'Email Can Not Be Empty'=> 'Email Can Not Be Empty',
        'Sorry This User Exist'=> 'Sorry This User Exist',
        'Add New Member'=> 'Add New Member',
        'Insert Member'=> 'Insert Member',
        'Sorry This User Is Exist'=> 'Sorry This User Is Exist',
        'Delete Member'=> 'Delete Member',
        'Setting'=> 'Setting',
        'Logout'=> 'Logout',
        'Edit Profile'=> 'Edit Profile',
        'Leave This Blank If you Do Not Want To change'=> 'Leave This Blank If you Do Not Want To change',
        'Admin Login'=> 'Admin Login',
        'Login'=> 'Login',
        'Attendance Time'=> 'Attendance Time',
        'Leaving Time'=> 'Leaving Time',
        'Date'=> 'Date',
        'Attendance'=> 'Attendance',
        'Days Of Attendance'=> 'Days Of Attendance',
        'Days Of Absence'=> 'Days Of Absence',
        'Add New Attendance'=> 'Add New Attendance',
        'Salary'=> 'Salary',
        'Edit Attendance'=> 'Edit Attendance',
        'Attend Or Not'=> 'Attend Or Not',
        'Attend'=> 'Attend',
        'Not Attend'=> 'Not Attend',
        'Update Attendance'=> 'Update Attendance',
        'Attendance Time Can Not Be Empty'=> 'Attendance Time Can Not Be Empty',
        'Edit Absence'=> 'Edit Absence',
        'Leaveing Time Can Not Be Empty'=> 'Leaveing Time Can Not Be Empty',
        'Attendance Date'=> 'Attendance Date',
        'Add Attendance'=> 'Add Attendance',
        'Insert Attendance'=> 'Insert Attendance',
        'Attendance Date Can Not Be Empty'=> 'Attendance Date Can Not Be Empty',
        'Attendance Time Can Not Be Empty'=> 'Attendance Time Can Not Be Empty',
        'Leaving Time Can Not Be Empty'=> 'Leaving Time Can Not Be Empty',
        'Delete Attendance'=> 'Delete Attendance',
        'Attend'=> 'Attend',
        'Add New Absence'=> 'Add New Absence',
        'Not Attend'=> 'Not Attend',
        'Absence Date'=> 'Absence Date',
        'Delete Absence'=> 'Delete Absence',
        'Not Collected'=> 'Not Collected',
        'Update Absence'=> 'Update Absence',
        'Add Absence'=> 'Add Absence',
        'Absence'=> 'Absence',
        'Insert Absence'=> 'Insert Absence',
        'Days Of Attendance And Absence'=> 'Days Of Attendance And Absence',
        'Total Salary'=> 'Total Salary',
        'Forget Password'=> 'Forget Password',
        'Send'=> 'Send',
        'Salary After Discounts'=> 'Salary After Discounts',
        'Salary'=> 'Salary',
        'Update Salary'=> 'Update Salary',
        'Insert Salary'=> 'Insert Salary',
        'Edit Salary'=> 'Edit Salary',
        'Take Salary Or Not'=> 'Take Salary Or Not',
        'Add Teacher Salary'=> 'Add Teacher Salary',
        'Total Salary Can Not Be Empty'=> 'Total Salary Can Not Be Empty',
        'Salary Can Not Be Empty'=> 'Salary Can Not Be Empty',
        'Salary Collected'=> 'Salary Collected',
        'Sorry This Teacher Has Salary'=> 'Sorry This Teacher Has Salary',
        'Salary Collect Can Not Be Empty'=> 'Salary Collect Can Not Be Empty',
        'Salary Not Collected'=> 'Salary Not Collected',
        'Salary After Discount'=> 'Salary After Discount',
        'Add Salary'=> 'Add Salary',
        'Delete ٍSalary'=> 'Delete ٍSalary',
        'View Salary'=> 'View Salary',
        'View Absence'=> 'View Absence',
        'Date'=> 'Date',
        'Not Paid'=> 'Not Paid',
        'View Attendance'=> 'View Attendance',
        'This Date Is Entered Before'=> 'This Date Is Entered Before',
        'Attend Or Not Can Not Be Empty'=> 'Attend Or Not Can Not Be Empty',
    );
    return $lang[$phrase]; // return value of key when calling it
}