<?php
$example_persons_array1 = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

function getPartsFromFullname (string $fullname){
    $fullname=mb_convert_case($fullname,MB_CASE_TITLE_SIMPLE);
    $separatorFullname = explode(' ',$fullname,3);
    $keys=['surname','name','patronomyc',];
    $partsFullname = array_combine($keys,$separatorFullname);
    return $partsFullname;}//функция преобразования ФИО в массив


function getFullnameFromParts (string $surname,string $name, string $patronomyc){
    $fullnameParts = $surname.' '.$name.' '.$patronomyc;
    return mb_convert_case($fullnameParts,MB_CASE_TITLE_SIMPLE);}//функция конкатенации фамилии,имени,отчества


function getShortName(string $fullname){
    $parts=getPartsFromFullname($fullname);
    $partsEncoding1=implode($parts);
    $partsEncoding2=mb_substr($partsEncoding1,0,1);
    $shortName = $parts['name'].' '.$partsEncoding2.'.';
    return $shortName;} //функция краткой записи Имени и инициала фамилии.


function getGenderFromName($fullname){
    $gender=getPartsFromFullname($fullname);
    $countGender=0;
        
    if (mb_substr($gender['patronomyc'],-3,3)=='вна')
    {$countGender -= 1;}
    else if (mb_substr($gender['patronomyc'],-2,2)=='ич')
    {$countGender += 1;}
        
    if (mb_substr($gender['name'],-1,1)=='а')
    {$countGender -= 1;}
    else if (mb_substr($gender['name'],-1,1)=='й'||'ч')
    {$countGender += 1;}
            
    if (mb_substr($gender['surname'],-2,2)=='ва')
    {$countGender -= 1;}
    else if (mb_substr($gender['surname'],-1,1)=='в')
    {$countGender += 1;}
        
    switch ($countGender){
        case $countGender==0:
            return 'Неопределённый пол';
                
        case $countGender<=-1:
            return 'Женский пол';   
                
        case $countGender>=1:
            return 'Мужской пол';
        }
}//функция определения пола
    


function getGenderDescription($array){
	function filteredMan($array) {
		if (getGenderFromName($array['fullname']) == 'Мужской пол'){
			return true;
			} else {return false;}
		}
    $Mans=array_filter($array,'filteredMan');
    
	function filteredWoman($array) {
		if (getGenderFromName($array['fullname']) == 'Женский пол'){
			return true;
			}else {return false;}
		}
    $Womans=array_filter($array,'filteredWoman');
   
    
   function filteredUndefined($array) {
		if (getGenderFromName($array['fullname']) == 'Неопределённый пол'){
			return true;
			}else {return false;}
		}
    $Undefined=array_filter($array,'filteredUndefined');
    
    $sumMans = count($Mans);
    $sumWomans = count($Womans);
    $sumUndefined = count($Undefined);

    $sumAll=count($array);
    $mansPercent=round($sumMans/$sumAll*100, 1);
    $womansPercent=round($sumWomans/$sumAll*100, 1);
    $othersPercent=round($sumUndefined/$sumAll*100, 1);
    $message=<<<HEREDOCTEXT
    Гендерный состав аудитории:
    Мужчины - {$mansPercent}%
    Женщины - {$womansPercent}%
    Не удалось определить - {$othersPercent}%
HEREDOCTEXT;
    return ($message);
    }
// функцию определения партнёра.

function getPerfectPartner($surname,$name,$patronomyc,$array){
    $SNP=getFullnameFromParts($surname,$name,$patronomyc);
    $SNPsex=getGenderFromName($SNP);
    $partner = $array[array_rand($array)]['fullname'];
    if ((getGenderFromName($partner) == $SNPsex)||($partner==$SNP)){
    	do {
        	$partner = $array[array_rand($array)]['fullname'];
    	} while (getGenderFromName($partner) != $SNPsex||$partner!=$SNP);
    }
    $sovmestimost=round(rand(50,100),2);
    $result = getShortName($SNP).' + '.getShortName($partner);
    return <<<NOWDOCTEXT
        $result
        'Идеально на $sovmestimost %'
NOWDOCTEXT;
}//функция определения пары

print_r(getPartsFromFullname('петров иван сергеевич'));
print_r(getFullnameFromParts('давыдов','роман','витальевич'));
print_r(getShortname('бронштейн мирослав олегович'));
print_r(getGenderFromName('Быстрая Юлия Сергеевна'));
print_r(getGenderDescription($example_persons_array1));
print_r(getPerfectPartner('николаев','игнат','романович',$example_persons_array1));