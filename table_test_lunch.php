<?php require 'headerAdmin.php'; ?>

    <?php require 'navigationAdmin.php'; ?>
   
    <div class="background--white margin-auto--left margin-auto--right padding-top--normal padding-bottom--normal padding-left--tiny padding-right--tiny">
<link rel="stylesheet" href="styles/jquery-ui.theme.min.css" />
<link rel="stylesheet" href="styles/gridstack_lunch.css" />
<link rel="stylesheet" href="styles/gridstack_custom.css" />
<script src="js/lodash.js"></script>
<script src="js/gridstack.js"></script>
<script src="js/gridstack.jQueryUI.js"></script>

<div class="grid-stack grid-stack-header">
    <div class="grid-stack-item"
        data-gs-x="3" data-gs-y="0"
        data-gs-width="2" data-gs-height="1"
        data-gs-no-resize="1" data-gs-no-move="1">
            <div class="grid-stack-item-content">13:00</div>
    </div>    
    <div class="grid-stack-item"
        data-gs-x="7" data-gs-y="0"
        data-gs-width="2" data-gs-height="1"
        data-gs-no-resize="1" data-gs-no-move="1">
            <div class="grid-stack-item-content">14:00</div>
    </div>   
    <div class="grid-stack-item"
        data-gs-x="11" data-gs-y="0"
        data-gs-width="1" data-gs-height="1"
        data-gs-no-resize="1" data-gs-no-move="1">
            <div class="grid-stack-item-content">15:00</div>
    </div>    
</div>

<div class="grid-stack grid-stack-animate">
    <div class="grid-stack-item"
        data-gs-x="0" data-gs-y="0"
        data-gs-width="4" data-gs-height="1">
            <div class="grid-stack-item-content">1</div>
    </div>    
<!--    <div class="grid-stack-item"
        data-gs-x="5" data-gs-y="0"
        data-gs-width="8" data-gs-height="1">
            <div class="grid-stack-item-content">Empty</div>
    </div>-->
</div>
<div class="grid-stack grid-stack-animate">
<!--    <div class="grid-stack-item"
        data-gs-x="0" data-gs-y="0"
        data-gs-width="8" data-gs-height="1">
            <div class="grid-stack-item-content">Empty</div>
    </div>-->
    <div class="grid-stack-item"
        data-gs-x="4" data-gs-y="0"
        data-gs-width="8" data-gs-height="1">
            <div class="grid-stack-item-content">2</div>
    </div>    
</div>
<div class="grid-stack grid-stack-animate">   
    <div class="grid-stack-item"
        data-gs-x="0" data-gs-y="0"
        data-gs-width="4" data-gs-height="1"
        data-gs-locked="1" data-gs-no-resize="1" 
        data-gs-no-move="1">
            <div class="grid-stack-item-content">3</div>
    </div>
<!--    <div class="grid-stack-item"
        data-gs-x="4" data-gs-y="0"
        data-gs-width="4" data-gs-height="1">
            <div class="grid-stack-item-content">Empty</div>
    </div>-->
    <div class="grid-stack-item"
        data-gs-x="9" data-gs-y="0"
        data-gs-width="4" data-gs-height="1">
            <div class="grid-stack-item-content">4</div>
    </div>    
</div>

<script src="js/gridstack.custom.js"></script>

    </div>

<?php require 'footer.php'; ?>
