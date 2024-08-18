<?php
    $example_persons_array = [
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
    //getPartsFromFullname принимает как аргумент одну строку — склеенное ФИО. 
    //Возвращает как результат массив из трёх элементов с ключами ‘name’, ‘surname’ и ‘patronomyc’.
    function getPartsFromFullname($stringInitials)
    {      
        $initialsArray = explode(' ', $stringInitials);
        return ['surname'=>$initialsArray[0],'name'=>$initialsArray[1],'patronomyc'=>$initialsArray[2]];
    }
    echo "Функция getPartsFromFullname:<br>";
    echo "<pre>";
    print_r(getPartsFromFullname('Иванов Иван Иванович'));
    echo "</pre>";
    
    // РАЗБИЕНИЕ И ОБЪЕДИНЕНИЕ ФИО
    function getFullnameFromParts($surname, $name, $patronomyc)
    {
        return $surname." ".$name." ".$patronomyc;
    }
    echo "Функция getFullnameFromParts:<br>";
    echo getFullnameFromParts('Иванов', 'Иван', 'Иванович') . "<br>";
    
    // СОКРАЩЕНИЕ ФИО
    function getShortName($stringInitials)
    {
        $initialsArray = getPartsFromFullname($stringInitials);
        return $initialsArray['name'].' '.mb_substr($initialsArray['surname'], 0, 1).'.';
    }
    echo "Функция getShortName:<br>";
    echo getShortName('Иванов Иван Иванович') . "<br>";
    
    // ФУНКЦИЯ ОПРЕДЕЛЕНИЯ ПОЛА ПО ФИО
    function getGenderFromName($stringInitials)
    {
        $initials = getPartsFromFullname($stringInitials);
        $genderSign = 0;
        if (mb_substr($initials['patronomyc'], -3, 3) === 'вна') {
            $genderSign--;
        }
        if (mb_substr($initials['name'], -1, 1) === 'а') {
            $genderSign--;
        }
        if (mb_substr($initials['surname'], -2, 2) === 'ва') {
            $genderSign--;
        }
        if (mb_substr($initials['patronomyc'], -2, 2) === 'ич') {
            $genderSign++;
        }
        if($initials['name'][-1]=='й' || $initials['name'][-1]=='н') {
            $genderSign++;
        }
        if (mb_substr($initials['surname'], -1, 1) === 'в') {
            $genderSign++;
        }
        return $genderSign<=>0;
    }
    echo "Функция getGenderFromName:<br>";
    echo "Пол: " . getGenderFromName('Иванов Иван Иванович') . "<br>";

    // ОПРЕДЕЛЕНИЕ ПОЛОВОГО СОСТАВА АУДИТОРИИ
    function getGenderDescription($arrayPersons)
    {
        $male=0;
        $female = 0;
        $undefined = 0;
        foreach($arrayPersons as $key){
            if(getGenderFromName($key['fullname'])==1) {
                $male++;
            }
            else if(getGenderFromName($key['fullname'])==-1) {
                $female++;
            }
            else{
                $undefined++;
            }
        }
        $total = count($arrayPersons);
        $maleCount = round($male / $total * 100, 1);
        $femaleCount = round($female / $total * 100, 1);
        $unknownCount = round($undefined / $total * 100, 1);
         $str= <<<WER
         <br>
         <span style="color:red;font-weight:500;">Гендерный состав аудитории:<br>
         --------------------------------------</span>
         <br>Мужчин - $maleCount%
         <br>Женщины - $femaleCount%
         <br>Не удалось определить - $unknownCount%
         <br>
         WER;
         return $str;

    } 
    
    
    echo getGenderDescription($example_persons_array);

    // СОВМЕСТИМОСТЬ
    function getPerfectPartner($surname, $name, $patronomyc, $array){ 
        $surname = mb_strtoupper(mb_substr($surname, 0,1)) . mb_strtolower(mb_substr($surname, 1));
        $name = mb_strtoupper(mb_substr($name, 0,1)) . mb_strtolower(mb_substr($name, 1));
        $patronomyc = mb_strtoupper(mb_substr($patronomyc, 0,1)) . mb_strtolower(mb_substr($patronomyc, 1));
      
        $currentUserFullname = getFullnameFromParts($surname, $name, $patronomyc);
        $currentUserShortName = getShortName($currentUserFullname);
      
        $currentUserGender = getGenderFromName($currentUserFullname);
      
        if ($currentUserGender == 0) {
          echo ("К сожалению, мы не смогли подобрать Вам идеальную пару");
        } else {                          
              do {
                $perfectPartnerIndex = rand(0, count($array)-1);
                $perfectPartnerFullname = $array[$perfectPartnerIndex]['fullname'];
                $perfectPartnerShortName = getShortName($perfectPartnerFullname);
                $perfectPartnerGender = getGenderFromName($perfectPartnerFullname);   
              }
              while ($perfectPartnerGender == $currentUserGender || $perfectPartnerGender == 0);
      
              $matchPercentage = rand(5000, 10000)/100;
      
      $perfectPartnerResult = 
      <<<MYHEREDOCTEXT
      $currentUserShortName + $perfectPartnerShortName =
      ♡ Идеально на $matchPercentage% ♡
      MYHEREDOCTEXT;
      
              return $perfectPartnerResult;                       
        }
      }
    echo "<br>СОВМЕСТИМОСТЬ:<br>";
    echo getPerfectPartner('Иванов', 'Иван', 'Иванович', $example_persons_array);

    

