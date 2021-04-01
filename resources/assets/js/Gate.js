export default class Gate{
    constructor(user){
        this.user = user;
    }
isAdmin(){
    return this.user.type === 'admin';
}
isUser(){
    return this.user.type === 'user';
}
isAdminOrAuthor(){
    if(this.user.type === 'admin' || this.user.type === 'author')
    return true;
}
isUserOrAuthor(){ // u can use only for user and auther
    if(this.user.type === 'user' || this.user.type === 'author')
    return true;
}

}
//3shan ma a5le el type user may2dar yfoot 3ala el Users aw developer bas el admin y2dar yfoot
//use ACL (authenticate) in the frontend