<?php
// Home
Breadcrumbs::register('home', function($breadcrumbs)
{
$breadcrumbs->push('Home', route('home'));
});
// BuyerMesage

Breadcrumbs::register('buyerJobsView', function($breadcrumbs,$category)
{
    
$breadcrumbs->push('Message', route('buyerMessage'));
$breadcrumbs->push(ucfirst($category['name']), route('buyerprojects_view','sellerid='.$category['id']));
});
Breadcrumbs::register('dashboardBuyerJobsView', function($breadcrumbs,$category)
{
    
$breadcrumbs->push('Dashboard', route('dashboard_buyer'));
$breadcrumbs->push(ucfirst($category['name']), route('buyerprojects_view','sellerid='.$category['id']));
});

Breadcrumbs::register('expertunsecured_roles', function($breadcrumbs)
{
$breadcrumbs->push('Dashboard', route('dashboard_expert'));
$breadcrumbs->push('Unsecured Roles', route('expertunsecured_roles'));
});
Breadcrumbs::register('expert_contract_view', function($breadcrumbs)
{
$breadcrumbs->push('Dashboard', route('dashboard_expert'));
$breadcrumbs->push('Contracts', route('expertsecured_roles'));
});
Breadcrumbs::register('expert_contract_detail', function($breadcrumbs,$category)
{
  
$breadcrumbs->parent('expert_contract_view');
$breadcrumbs->push(ucfirst($category['name']), route('contract_view','contract_view?contractid='.$category['id']));
});
Breadcrumbs::register('contract_detail', function($breadcrumbs,$category)
{
  
$breadcrumbs->push('Dashboard', route('dashboard_expert'));
$breadcrumbs->push(ucfirst($category['name']), route('contract_view','contract_view?contractid='.$category['id']));
});
Breadcrumbs::register('unsecureJobView', function($breadcrumbs,$category)
{
    
$breadcrumbs->parent('buyerunsecured_roles');
$breadcrumbs->push(ucfirst($category['name']), route('buyerprojects_view','sellerid='.$category['id']));
});

Breadcrumbs::register('unsecureExpertProjectView', function($breadcrumbs,$category)
{

$breadcrumbs->parent('expertunsecured_roles');
$breadcrumbs->push(ucfirst($category['name']), route('projects_view','sellerid='.$category['id']));
});

Breadcrumbs::register('buyerMessaging', function($breadcrumbs,$category)
{
$breadcrumbs->push('Message', route('buyerMessage'));
$breadcrumbs->push(ucfirst(trim($category['name'])."'s Profile"), route('expert-profile-detail','sellerid='.$category['id']));
});
Breadcrumbs::register('otherBuyerMessaging', function($breadcrumbs,$category)
{
$breadcrumbs->push('Message', route('buyerMessage'));
$breadcrumbs->push('Expert', route('expert-profile-detail','sellerid='.$category['expert_id'].'&breadcrumb-page=buyerMessaging'));
$breadcrumbs->push(ucfirst(trim($category['name'])."'s Profile"), route('expert-profile-detail','sellerid='.$category['id']));
});
// ExpertMessage
// BuyerMesage
Breadcrumbs::register('expertMessaging', function($breadcrumbs,$category)
{
$breadcrumbs->push('Message', route('expertMessage'));
$breadcrumbs->push(ucfirst(trim($category['name'])."'s Profile"), route('buyer-detail-page','id='.$category['id']));
});
Breadcrumbs::register('project_view', function($breadcrumbs,$category)
{
$breadcrumbs->push('Browse Projects', route('projects-search'));
$breadcrumbs->push(ucfirst(trim($category['name'])), route('buyer-detail-page','id='.$category['id']));
});
Breadcrumbs::register('projects_view', function($breadcrumbs,$category)
{
$breadcrumbs->push('Messages', route('expertMessage'));
$breadcrumbs->push(ucfirst(trim($category['name'])), route('projects_view','sellerid='.$category['id']));
});
Breadcrumbs::register('dashboard_expert_projects_view', function($breadcrumbs,$category)
{
$breadcrumbs->push('Dashboard', route('dashboard_expert'));
$breadcrumbs->push(ucfirst(trim($category['name'])), route('projects_view','sellerid='.$category['id']));
});

Breadcrumbs::register('buyer-search', function($breadcrumbs,$category)
{
$breadcrumbs->push('Browse Experts', route('buyer-search'));
$breadcrumbs->push(ucfirst(trim($category['name'])."'s Profile"), route('expert-profile-detail','sellerid='.$category['id']));
});
Breadcrumbs::register('other-buyer-search', function($breadcrumbs,$category)
{
$breadcrumbs->push('Browse Experts', route('buyer-search'));
$breadcrumbs->push('Expert', route('expert-profile-detail','sellerid='.$category['expert_id'].'&breadcrumb-page=buyer-search'));
$breadcrumbs->push(ucfirst(trim($category['name'])."'s Profile"), route('expert-profile-detail','sellerid='.$category['id']));
});
Breadcrumbs::register('view-service-package', function($breadcrumbs,$category)
{
$breadcrumbs->push($category['title'], route('view-service-package',$category['service_package_id']));
$breadcrumbs->push($category['name'],route('expert-profile-detail','sellerid='.$category['id']));
});


Breadcrumbs::register('expert-profile', function($breadcrumbs, $category) {
    if (strpos($category['referer'], 'messages') !== false) {
        $breadcrumbs->push('Messages', route('buyerMessage'));
    }else {
        $breadcrumbs->push('Browse Experts', route('buyer-search'));
    }
    $breadcrumbs->push(ucfirst(trim($category['name'])));
});
