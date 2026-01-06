<script>

    function toggleInterestSection() {

        var selectedValue = document.getElementById('lucky_draw').value;

        if (selectedValue == "0") {
            document.getElementById('interest_main_sec').style.display = 'block';
        } else {

            document.getElementById('interest_main_sec').style.display = 'none';
            document.getElementById('interest_no').checked = true ;
            toggleInterestBlock(); // Ensure that the interest block is hidden

        }
    }

    function toggleInterestBlock() {
        var interestYes = document.getElementById('interest_yes').checked;
        if (interestYes) {
            document.getElementById('interest_block').style.display = 'block';
        } else {
            document.getElementById('interest_block').style.display = 'none';
        }
    }

    toggleInterestSection() ;

</script>