function encodeMessage(msg, hashPass) {
    if (!msg || !hashPass) {
        return;
    }
    // Convert pass to bytes
    var passBytes = aesjs.utils.utf8.toBytes(hashPass);
    // Convert message to bytes
    var textBytes = aesjs.utils.utf8.toBytes(msg);


    // The counter is optional, and if omitted will begin at 1
    var aesCtr = new aesjs.ModeOfOperation.ctr(passBytes, new aesjs.Counter(5));
    var encryptedBytes = aesCtr.encrypt(textBytes);

    return aesjs.utils.hex.fromBytes(encryptedBytes);
}

function decodeMessage(hex, hashPass) {
    if (!hex || !hashPass) {
        return;
    }
    var passBytes = aesjs.utils.utf8.toBytes(hashPass);

    var encryptedBytes = aesjs.utils.hex.toBytes(hex);

    // The counter mode of operation maintains internal state, so to
    // decrypt a new instance must be instantiated.
    var aesCtr = new aesjs.ModeOfOperation.ctr(passBytes, new aesjs.Counter(5));
    var decryptedBytes = aesCtr.decrypt(encryptedBytes);

    // Convert our bytes back into text
    return aesjs.utils.utf8.fromBytes(decryptedBytes);
}