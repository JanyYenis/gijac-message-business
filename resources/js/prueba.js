const crypto = require('crypto');

const secret = 'haluwbsh2rcw90fucv644wzeetwjp5kh'; // Your verification secret key
const userId = current_user.id // A string UUID to identify your user

const hash = crypto.createHmac('sha256', secret).update(userId).digest('hex');