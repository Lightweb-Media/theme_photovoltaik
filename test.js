document.addEventListener('DOMContentLoaded', () => {
    const parent = document.querySelector('.pv-calculator')

    parent.addEventListener( 'click', (e) => {
        console.log( e.target, e.target.type )
        if( e.target.type === 'radio' ) {
            document.querySelector('.frm_button_submit').click()
        }
    } )
})