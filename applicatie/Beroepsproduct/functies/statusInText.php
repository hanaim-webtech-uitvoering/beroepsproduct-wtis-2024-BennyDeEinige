<?php
//hier zorg ik er alvast voor dat de int's worden omgezet naar strings(kan nog toegevoegd worden en/of aangepast worden).
function getStatusText($status) {
    switch ($status) {
        case 1:
            return "Ontvangen";
        case 2:
            return "Wordt voorbereid";
        case 3:
            return "Wordt bezorgd";
        default:
            return "Onbekend";
    }
}
?>