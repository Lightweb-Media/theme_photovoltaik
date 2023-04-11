const HERO_TEXT = document.querySelector('.hero-width-calculator div:nth-child(1)');
const HERO = document.querySelector('.hero-width-calculator');
const CALCULATOR = document.querySelector('.hero-width-calculator div.pv-calculator');
const CALCULATOR_TOGGLE = document.querySelector('.pv-calculator-toggle');
const ORIGINAL_TOGGLE_TEXT = CALCULATOR_TOGGLE?.querySelector('.gb-button-text').textContent;
let CALCULATOR_OPEN = false;

CALCULATOR.style.display = 'none';


CALCULATOR_TOGGLE.addEventListener('click', () => {
    CALCULATOR_OPEN = !CALCULATOR_OPEN;

    if(CALCULATOR_OPEN == false){
        //HERO_TEXT.style.transform = 'none';
        //CALCULATOR.style.transform = 'translateX(100%)';
        HERO.style.transform = 'none';
        CALCULATOR.style.display = 'none';
        CALCULATOR_TOGGLE.querySelector('.gb-button-text').textContent = ORIGINAL_TOGGLE_TEXT;
    }else{
        //HERO_TEXT.style.transform = 'translateX(-100%)';
        //CALCULATOR.style.transform = 'none';
        HERO.style.transform = 'translateX(-100%)';
        CALCULATOR.style.display = 'block';
        
        CALCULATOR_TOGGLE.querySelector('.gb-button-text').textContent = "Rechner schließen";
    }
  });

