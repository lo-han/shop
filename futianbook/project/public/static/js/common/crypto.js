/**
 * AES加密
 * @param content   加密内容
 * @param key   密钥
 * @returns {string}
 */
function aesEncrypt(content, key) {
    var key1 = CryptoJS.enc.Utf8.parse(key);
    var srcs = CryptoJS.enc.Utf8.parse(content);
    var encrypted = CryptoJS.AES.encrypt(srcs, key1, {mode: CryptoJS.mode.ECB, padding: CryptoJS.pad.Pkcs7});
    return encrypted.toString();
}

/**
 * AES解密
 * @param content   解密内容
 * @param key   密钥
 * @returns {string}
 */
function aesDecrypt(content, key) {
    var key1 = CryptoJS.enc.Utf8.parse(key);
    var decrypted = CryptoJS.AES.decrypt(content, key1, {mode: CryptoJS.mode.ECB, padding: CryptoJS.pad.Pkcs7});
    return CryptoJS.enc.Utf8.stringify(decrypted).toString();
}