<?php

namespace App\Http\Controllers;

use App\Model\{
    User, UserProfile
};
use App\Model\Skill;
use App\Model\UsersSkill;
use App\Model\BuyerProfile;
use App\Model\Tag;
use Illuminate\Http\Request;
use Auth;
class TechnographicController extends Controller
{

    function search(Request $request)
    {
        $inputs = $request->all();
        $domain = $request->get('domain') ?? '';
        $logo = $request->get('logo') ?? url('images/unknown-logo.svg',[],getenv('APP_SSL'));
        $company_name = $request->get('company_name') ?? '';
        $default_skill_limit = config('constants.DEFAULT_POPULAR_SKILL_LIMIT');
        $found_skills_ids = $skill_logos = $skills_result = $experts_result = $expert_ids = $profile_pictures = [];
        $found_skills_ids_json_encoded = '';
        $is_ajax_request = 1;
        $countries_count = 0;
        if (isset($inputs['more_popular_skill']))
        {
            $hidden_skills = trim($request->hidden_skills);
            if (!empty($hidden_skills))
                $found_skills_ids = json_decode($hidden_skills, true);
            $limit = config('constants.TOTAL_POPULAR_SKILL__TO_BE_DISPLAY_LIMIT') - $default_skill_limit;
            $popular_skills = (new UsersSkill())->getPopularSkills($found_skills_ids, $limit, $default_skill_limit, $this->includeSkills());
            return view('technographic.partial_more_skill', compact('popular_skills', 'is_ajax_request'));
        }
        $skills_to_search = $this->getBuildWithSkills($domain);
        $skills_to_search = $this->finalSkills($skills_to_search, $this->skillToBeExclude());
        
        $skill_result_query = _count($skills_to_search) ? $this->getSkillSearchQuery($skills_to_search, 1) : [];
        $tags_result_query = _count($skills_to_search) ? $this->getTagSearchQuery($skills_to_search, 1) : [];
        $skill_result = _count($skills_to_search) ? $skill_result_query->get()->toArray() : [];
        $tag_result = _count($skills_to_search) ? $tags_result_query->get()->toArray() : [];
        if (_count($skill_result))
        {
            $expert_result = $this->skillBasedExpertSearch($skill_result);
        }
        
        if(_count($tag_result))
        {
            $tag_based_expert_result = $this->tagBasedExpertSearch($tag_result);
            if(_count($skill_result))
                $expert_result = $expert_result->union($tag_based_expert_result);
            else
                $expert_result = $tag_based_expert_result;
        }
        
        if(_count($tag_result) || _count($skills_result))
        {
            $skills_result = (new User)->groupUsers($expert_result);
            $skills_result = $this->updateSkillsResults($skills_result);
            $all_matching_skills_and_tags = array_pluck($skills_result, 'skill_name');
            $all_matching_skills_and_tags = array_map('strtolower', $all_matching_skills_and_tags);
            $all_skill_details = (new Skill)->skillLogos($all_matching_skills_and_tags);
            $found_skills_ids = array_pluck($all_skill_details, 'id');
            $found_skills_ids_json_encoded = json_encode($found_skills_ids);
            $skill_logos = $this->skillLogoImages($all_skill_details);
            
        }
        
        if (_count($skills_result))
        {
            $experts_details = $this->expertListing($skills_result);
            $experts_result = $experts_details['result'];
            $expert_ids = array_unique($experts_details['expert_ids']);
            $profile_pictures = $experts_details['profile_pictures'];
            $countries_count = (new UserProfile)->expertCountriesCount($expert_ids);
        }
       
        $limit = config('constants.DEFAULT_POPULAR_SKILL_LIMIT');
        $offset = 0;
        $popular_skills = (new UsersSkill())->getPopularSkills($found_skills_ids, $limit, $offset, $this->includeSkills());

        return view('technographic.result', compact(
                'popular_skills',
                'domain',
                'experts_result',
                'expert_ids',
                'countries_count',
                'found_skills_ids_json_encoded',
                'is_ajax_request',
                'company_name',
                'profile_pictures',
                'logo',
                'skill_logos')
        )->render();
    }
    
    private function skillLogoImages($skill_result)
    {
        $logo_urls = [];
        foreach($skill_result as $result)
        {
            $logo_urls[strtolower($result['name'])] = $result['logo_url'];
        }
        return $logo_urls;
    }

    public function loadingPage(Request $request)
    {
        $is_ajax_request = 0;
        $logo = $request->logo ?? url('images/unknown-logo.svg',[],getenv('APP_SSL'));
        $domain = $request->domain ?? '';
        $name = $request->name ?? '';
        return view('technographic.loading_screen', compact('is_ajax_request', 'domain', 'logo', 'name'));
    }

    private function expertListing($skills_result)
    {
        $experts_result = $profile_pictures = $all_experts = [];
        foreach ($skills_result as $skill_data)
        {
            $expert_ids = explode(',', substr($skill_data['user_ids'], 1, -1));
            $experts_result[$skill_data['skill_name']] = $expert_ids;
            $all_experts = array_merge($all_experts, $expert_ids);
        }
        if(_count($all_experts))
            $profile_pictures = (new UserProfile)->profilePictures($all_experts);
        
        return ['result' => $experts_result,
                'profile_pictures' => $profile_pictures,
                'expert_ids' => $all_experts];
    }


    private function getBuildWithSkills($domain) {
        if ($domain !== '') {
            if(Auth::check())
            {
                $user_id = Auth::user()->id;
                $buyer_data = BuyerProfile::getBuyerDetail($user_id);
                $company_url = $buyer_data->company_url ?? '';
                if(trim($company_url) == trim($domain))
                {
                    if((session()->has('built_with_response_' . Auth::user()->id)))
                    {
                        return session()->get('built_with_response_'.Auth::user()->id);
                    }
                    
                    $built_with_data = $this->builtWithData($domain);
                    session()->put('built_with_response_'.Auth::user()->id, $built_with_data);
                    return $built_with_data;
                }
            }
                
            $built_with_data = $this->builtWithData($domain);
            return $built_with_data;
        }
    }
    
    private function builtWithData($domain)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.builtwith.com/v12/api.json?KEY='.env('BUILD_WITH_API_KEY').'&LOOKUP='.$domain.'&LIVEONLY=yes&HIDEDL=yes&NOATTR=yes&NOMETA=yes',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 1000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, 1);
        $final_data = [];
        $excluded_tags = $this->excludeTags();
        if (isset($response['Results'][0]['Result']['Paths'][0]['Technologies'])) {
            foreach ($response['Results'][0]['Result']['Paths'][0]['Technologies'] as $skill) {
                if (!in_array($skill['Tag'], $excluded_tags)) {
                    $final_data [] = $skill['Name'];
                }
            }
        }
        return $final_data;
    }

    public function getClearbitData(Request $request) {
        $term = $request->get('query') ?? '';
        $response = getClearbitData($term);
        return $this->tructateCompanyName(json_decode($response, 1));
    }
    
    public function createTechnographicBuyerUrl()
    {
        if(buyerAuth())
        {
            $query_string = technographicBuyerUrl();
            return redirect('/technographic-info'.$query_string);
        }
        return redirect('login');
    }

    private function tructateCompanyName($results = []) {
        $final_result = [];
        if (is_array($results)) {
            foreach ($results as $result) {
                $final_result [] = [
                    'logo' => $result['logo'] ?? '',
                    'domain' => $result['domain'] ?? '' ,
                    'name' => $result['name'] ?? '',
                    'truncate_name' => getTruncatedContent($result['name'] ?? '', config('constants.TECHNOGRAPHIC_COMPANY_NAME_LIMIT'))
                ];
            }
            return $final_result;
        }
        return [];
    }

    private function getSkillSearchQuery($skills_to_search, $priority)
    {
        $raw_query = [];
        $result = [];
            foreach ($skills_to_search as $skill)
                $raw_query[] = $this->skillSearchQuery($skill, $priority);

        if (_count($raw_query))
            $result = $this->consolidatedQuery($raw_query);

        return $result;
    }
    
    private function getTagSearchQuery($skills_to_search, $priority)
    {
        $raw_query = [];
        $result = [];
            foreach ($skills_to_search as $skill)
                $raw_query[] = $this->tagSearchQuery($skill, $priority);

        if (_count($raw_query))
            $result = $this->consolidatedQuery($raw_query);

        return $result;
    }

    private function consolidatedQuery($raw_query)
    {
        $count = 0;
        foreach ($raw_query as $query)
        {
            if (!$count)
                $result = $query;
            if ($count)
                $result->union($query);
            $count++;
        }
        return $result;
    }

    private function skillBasedExpertSearch($skills_data)
    {
        $user_result = [];
        $result = [];

        foreach ($skills_data as $user_skill)
        {
            $user_result[] = User::expertSearchBasisOfSkillsInProfile($user_skill['priority'], $user_skill['skill_name'], $user_skill['id']);
        }
            

        if (_count($user_result))
            $result = $this->consolidatedQuery($user_result);

        return $result;
    }
    
    private function tagBasedExpertSearch($tags_data)
    {
        $user_result = [];
        $result = [];

        foreach ($tags_data as $tags)
        {
            $user_result[] = User::expertSearchBasisOfTags($tags['priority'], $tags['skill_name'], $tags['id']);
        }
            

        if (_count($user_result))
            $result = $this->consolidatedQuery($user_result);

        return $result;
    }

    private function skillSearchQuery($skill, $priority)
    {
        $skills = new Skill;
        return $skills->fetchSkillsQuery($skill, $priority);
    }
    
    private function tagSearchQuery($skill, $priority)
    {
        $skills = new Tag;
        return $skills->fetchTagQuery($skill, $priority);
    }

    private function skillToBeExclude() {
        return [
            'Google font API',
            'Google',
            'DoubleClick.net',
            'Facebook SDK',
            'PHP',
            'AffiliateWP',
            'Google Cloud DNS',
            'Shutterstock',
            'Apple Mobile Web Clips Icon',
            'Viewport Meta',
            'Google Hosted Libraries',
            'Google Hosted Web Font Loader',
            'jQuery Cookie',
            'Google Hosted jQuery',
            'Wordpress',
            'Godaddy',
            'WebFont Loader',
            'Pound Sterling',
            'CDN JS',
            'GStatic Google Static Content',
            'Smart App Banner',
            'Are You a Human',
            'Fonts.com',
            'English HREF LANG',
            'German HREF LANG',
            'Chinese HREF LANG',
            'Spanish HREF LANG',
            'French HREF LANG',
            'Shockwave Flash Embed',
            'IPhone / Mobile Compatible',
            'Mobile Non Scaleable Content',
            'html5shiv',
            'DHL',
            'Advertising.com',
        ];
    }

    private function finalSkills($buildwith_response, $exclude_skills) {
        if($buildwith_response){
            foreach($buildwith_response as $index => $skill) {
                foreach($exclude_skills as $exclude_skill) {
                    if (strtolower($exclude_skill) == strtolower($skill)) {
                        unset($buildwith_response[$index]);
                    }
                }
            }
            return array_values($buildwith_response);
        }
    }

    private function includeSkills() {
        return [
            'A/b Testing',
            'Google Analytics',
            'Tableau',
            'Adobe Analytics',
            'Google Data Studio',
            'Google Tag Manager',
            'Google Analytics 360',
            'google optimize',
            'Optimizely',
            'Adobe Dynamic Tag Management',
            'Power BI',
            'Salesforce',
            'Adobe Target',
            'AWS',
            'Tealium',
            'MailChimp',
            'DoubleClick',
            'hotjar',
            'HubSpot',
            'Mixpanel',
            'Alteryx',
        ];
    }

    private function excludeTags() {
        return [
            'cdn',
            'cms',
            'copyright',
            'encoding',
            'docinfo',
            'parked',
            'mx',
            'language',
            'mapping',
            'mobile'
        ];
    }

    public function updateSkillsAliases(){
        try{
            $import_aliases = file_get_contents(url('aliases.csv'));
        } catch (\Exception $e){
            echo 'aliases.csv not found';
        }
        if (!empty($import_aliases)) {
            $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $import_aliases));

            $found_results = [];
            $found_diff = [];

            foreach ($data as $skill) {
                $skill_name = trim($skill[1], "\xC2\xA0\x20");
                $skill_alias = trim($skill[0], "\xC2\xA0\x20");
                $check_skill = Skill::searchSkills(trim($skill_name))->get()->toArray();
                if(!empty($check_skill)) {
                    $found['name'] = $skill_name;
                    $found['alias'] = $skill_alias;
                    $found['id'] = $check_skill[0]['id'];
                    if (strtolower($found['name']) != strtolower($found['alias'])){
                        array_push($found_diff, $found);
                        Skill::updateAlias($found['id'], $found['alias']);

                    } else {
                        array_push($found_results, $found);
                    }
                }
            }
            return ['diff_count' => sizeof($found_diff), 'found_diff' => $found_diff, 'results_count' => sizeof($found_results), 'results' => $found_results];
        }
    }

    private function updateSkillsNames($experts_result){

        $new_experts_result = [];

        foreach($experts_result as $skill_name => $expert_data){

            $alias = Skill::getSkillByAlias($skill_name)->get()->first();

            if ($alias){

                $new_experts_result[$alias->name] = $expert_data;

            } else {
                $new_experts_result[$skill_name] = $expert_data;
            }
        }
        return $new_experts_result;
    }

    private function updateSkillsResults($skills_result){

        $new_skills_result = [];

        foreach ($skills_result as $skill) {
            $alias = Skill::getSkillByAlias($skill['skill_name'])->get()->first();
            if ($alias){
                $skill['skill_name'] = $alias->name;
            }
            array_push($new_skills_result, $skill);
        }

        return $new_skills_result;
    }
}
