<?php
require_once('/var/www/html/vendor/autoload.php');
putenv('GOOGLE_APPLICATION_CREDENTIALS=/opt/data/keys/manifest-design-95119-e210c3de7a09.json');


function create_google_group($group_name, $group_email){
  $client = new Google_Client();
  $client->useApplicationDefaultCredentials();
  $client->setSubject("joe.gange@osisd.net");
  $client->setScopes(['https://www.googleapis.com/auth/admin.directory.user','https://www.googleapis.com/auth/admin.directory.group']);
  $service = new Google_Service_Directory($client);

  $newGroup = new Google_Service_Directory_Group;
  $newGroup->setName($group_name);
  $newGroup->setEmail($group_email);
  $service->groups->insert($newGroup);
}
function delete_google_group($group_email) {
  $client = new Google_Client();
  $client->useApplicationDefaultCredentials();
  $client->setSubject("joe.gange@osisd.net");
  $client->setScopes(['https://www.googleapis.com/auth/admin.directory.user','https://www.googleapis.com/auth/admin.directory.group']);
  $service = new Google_Service_Directory($client);
  $service->groups->delete($group_email);
}
function add_google_group_member($group_name, $user_email){
  $client = new Google_Client();
  $client->useApplicationDefaultCredentials();
  $client->setSubject("joe.gange@osisd.net");
  $client->setScopes(['https://www.googleapis.com/auth/admin.directory.user','https://www.googleapis.com/auth/admin.directory.group']);
  $service = new Google_Service_Directory($client);
  $newMember = new Google_Service_Directory_Member;
  $newMember->setEmail($user_email);
  $newMember->setRole('MEMBER');
  $service->members->insert($group_name, $newMember);
}
function remove_google_group_member($group_name, $user_email){
  $client = new Google_Client();
  $client->useApplicationDefaultCredentials();
  $client->setSubject("joe.gange@osisd.net");
  $client->setScopes(['https://www.googleapis.com/auth/admin.directory.user','https://www.googleapis.com/auth/admin.directory.group']);
  $service = new Google_Service_Directory($client);
  $service->members->delete($group_name, $newMember);
}
//function update_google_group($group_name,$group_email) {
//  $newGroup = new Google_Service_Directory_Group;
//  $newGroup->setName($group_name);
//  $newGroup->setEmail($group_email);
//  $results = $service->groups->update($newGroup);
//}
