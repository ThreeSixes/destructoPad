        <br/>
        <span class="tinyText">DestructoPad is an open source project allows you to create short text notes
(up to <?php echo $dp->getMaxPadSize(); ?> bytes) and is designed to run over the TOR network as a hidden service.
These notes or "pads" are stored on the server using encryption (<?php echo $dp->getEncryptionAlgo(); ?>). As soon as you create the pad you'll be given a URL
which you can give to someone to read. As soon as they open the pad it is deleted from the server, and pads
automatically expire between <?php $exprTime = $dp->getPadExpireRange(); echo $exprTime['min'] . " and " . $exprTime['max'];  ?> hours if they're not read.
All pads are automatically encoded as UTF-8, and are returned with a text/plain MIME type.</span>
    </body>
</html>
