export const setMessage= (state , message) =>{
   state.message =message;
}


export const setComments= (state , comments) =>{
   state.comments =comments;
}

export const setLoading= (state , trueOrFalse) =>{
   state.loading = trueOrFalse;
}


export const setUser= (state , user) =>{
   state.user = user;
}

export const setCommentsMeta= (state , meta) =>{
   state.commentsMeta =meta;
}


export const setBuyOrRent= (state , buyOrRent) =>{
   state.buyOrRent =buyOrRent;
}

export const setTitle= (state , title) =>{
   state.title =title;
}

export const setCartTotal= (state , t) =>{
   state.cartTotal =t;
}


export const setCartId= (state , id) =>{
   state.cart_id =id;
}


export const showLoginForm= (state , trueOrFalse) =>{
   state.showLoginForm =trueOrFalse;
}


export const showPaymentForm= (state , trueOrFalse) =>{
   state.showPayemtForm =trueOrFalse;
}


export const setNotification= (state , notification) =>{
   state.notification =notification;
}


export const clearMessage= (state , meta) =>{
   state.message =null;
}


export const appendToWishlist= (state , wishlist) =>{
   state.wishlist =wishlist;
}

export const loggedIn = (state , trueOrFalse) =>{
   state.loggedIn = trueOrFalse;
}


export const setFormErrors = (state,errors) => {
   state.errors = errors  
}












 
