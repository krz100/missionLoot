<?php 

class generateCode {
    //dostepne znaki
    private $characters;
    //ilość dostępnych znaków
    private $charactersLength;
    //dostępne znaki w losowej kolejności
    private $charactersKey = array();
    //długość kodu
    private $length = 2;
    //ilość kodów
    private $count = 100;
    //kolejność ustawiania znaków
    private $order = array();
    //maksymalna długość kodu potrzebna do wygenerowania wymaganej ilości kodów
    private $maxLengthNeed;
    
    function __construct() {
        $this->init();
    }
    
    /**
     * ustawienie danych początkowych
     */
    public function init(): void {
        $this->characters = array('0','1','2','3','4','5','6','7','8','9',
        'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        /*'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'*/
        );
        $this->charactersLength = count($this->characters)-1;
        //wymieszanie kolejności znaków
        shuffle($this->characters);
        //przypisanie losowym znakom kolejności
        for($i=0;$i<=$this->charactersLength;++$i) {
            $this->charactersKey[$this->characters[$i]] = $i;
        }
        //kolejność zmian w kodzie
        for($i=0;$i<$this->length;++$i) {
            $this->order[$i] = $i;
        }
        //ustawienie losowej kolejność zmian w kodzie
        shuffle($this->order);
    }
    
    /**
     * wygenerowanie listy unikalnych kodów
     * @param int $length
     * @param int $count
     * @return array
     */
    public function generateCodes(int $length = 0, int $count = 0): array {
        $this->setLength($length);
        $this->setCount($count);
        $list = [];
        if($this->canGenerate()) {
            $list = $this->generator();
        }
        return $list;
    }
    
    /**
     * ustawienie długości kodu
     * @param int $length
     */
    public function setLength(int $length): void {
        if($length > 0) {
            $this->length = $length;
        }
    }
    
    /**
     * ustawienie ilości kodów
     * @param int $count
     */
    public function setCount(int $count): void {
        if($count > 0) {
            $this->count = $count;   
        }
    }
    
    /**
     * generowanie losowego kodu
     * @return string
     */
    private function generateOneCode(): string {
        $first = '';
        for($i=0;$i<$this->length;++$i) {
            $first .= $this->randomCharacter();
        }
        return $first;
    }
    
    /**
     * losowy znak
     * @return string
     */
    private function randomCharacter(): string {
        return $this->characters[rand(0, $this->charactersLength)];;
    }
    
    /**
     * generowanie kolejnego unikalnego kodu
     * @param string $code
     * @return string
     */
    private function generateNextCode(string $code): string {
        $add = true;
        $i = 0;
        //podniesienie numeru kodu o 1
        while($add == true) {
            if($this->length > $i) {
                $indexI = $this->order[$i];
                $index = $this->charactersKey[$code[$indexI]]+1;
                $charactersLength = $this->charactersLength+1;
                if($index < $charactersLength) {
                    $code[$indexI] = $this->characters[$index];
                    $add = false;
                } else {
                    $code[$indexI] = $this->characters[0];
                    $i++;
                }
            } else {
                $add = false;
            }
        }
        //ustawienie pozostałych znaków na nosowe
        for($i=$this->maxLengthNeed;$i<$this->length;++$i) {
            $indexI = $this->order[$i];
            $code[$indexI] = $this->randomCharacter();
        }
        return $code;
    }

    /**
     * sprawdzenie czy można wygenerować zadaną ilość unikalnych kodów
     * @return bool
     */
    private function canGenerate(): bool {
        if($this->length > 0) {
            $maxUniqId = 1;
            $charactersLength = $this->charactersLength+1;
            $i=0;
            while($i<$this->length && $maxUniqId <= $this->count) {
                $maxUniqId *= $charactersLength;
                $i++;
            }
            if($maxUniqId >= $this->count) {
                $this->maxLengthNeed = $i;
                return true;
            } else {
                //error nie można wygenerować wystarczającej ilości unikalnych kodów
                throw new Exception('Szukając większej ilości skarbów napadli cię bandyci. Nie można znaleść wystarczającej ilości unikalnych łupów dla tej misji', 422);
            }
        }
        //error kod musi składać się z przynajmniej jednego znaku
        throw new Exception('Kody muszą składać się z przynajmniej jednego znaku', 422);
    }
    
    /**
     * generator kodów na podstawie podnoszenia licznika
     * @param int $length
     * @param int $count
     * @return array
     */
    private function generator(): array {
        $list = [];
        $first = $this->generateOneCode();
        $list[0] = $first;
        for($i=1;$i<$this->count;++$i) {
            $list[] = $this->generateNextCode($list[$i-1]);
        }
        return $list;
    }
}
