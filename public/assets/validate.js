
function validateString(str, msg) {
    if (str == "" || str == null || str == undefined)
        throw msg
}

function validateEmail(str, msg) {
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailRegex.test(str))
        throw msg
}

function validateLenght(str, minlen, msg) {
    if (str.length < minlen)
        throw msg
}

function validatePhoneNumber(phone, msg) {
    const regex = /^(?:\+?\d{1,2}\s?)?(\(?\d{3}\)?[\s\-]?)\d{3}[\s\-]?\d{4}$/;
    if (!regex.test(phone))
        throw msg
}

function validatePostCode(postCode, msg) {
    const regex = /^[A-Za-z0-9\s\-]{3,10}$/;
    if (!regex.test(postCode))
        throw msg
}


function validateFile(file, size, msg) {
    let ext = ['png', 'jpg', 'jpeg']
    console.log(file.length)
    if (file.length == 0) {
        throw msg
    }
    for (let f of file) {
        if (ext.indexOf(f.name.split('.')[1]) == -1) {
            throw msg + " with type png, jpg or jpeg"
        }
        if (parseInt(f.size) / 1024 / 1024 > size) {
            throw msg + " with maximum size of " + size + ' mb'
        }
    }
}