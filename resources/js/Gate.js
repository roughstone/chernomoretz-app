export default class Gate{

    constructor(user){
            this.user = user;
    }

    isAdmin() {
        return this.user.role === 'admin';
    }

    isModelator() {
        return this.user.role === 'modelator';
    }

    isUser() {
        return this.user;
    }
}
