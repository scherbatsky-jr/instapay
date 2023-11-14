import {
    logout as AttempLogout,
    login as AttemptLogin,
    refreshToken as AttemptRefreshToken,
    resetPassword as AttemptResetPassword,
    resetPasswordRequest as AttemptResetPasswordRequest,
    signUp as AttemptSignUp,
  } from "@/api/auth";
  
  import { defineStore } from "pinia";
  import router from "@/router";
  import storage from "@/storage";
  
  export const useAuthStore = defineStore("auth", {
    actions: {
      async login(credential) {
        return AttemptLogin(credential).then(({ authTokens, user }) => {
          this.user = user;
          this.authTokens = authTokens;
          this.expiresAt = authTokens.expiresIn
            ? Date.now() + authTokens.expiresIn * 1000
            : null;
  
          storage.setItem("___auth_user", JSON.stringify(user));
          storage.setItem("___auth_authTokens", JSON.stringify(authTokens));
          storage.setItem("___auth_expiresAt", JSON.stringify(this.expiresAt));
  
          return user;
        });
      },
  
      async logout() {
        this.user = null;
        this.authTokens = null;
        this.expiresAt = null;
  
        storage.removeItem("___auth_user");
        storage.removeItem("___auth_authTokens");
        storage.removeItem("___auth_expiresAt");
  
        router.push({ name: "login" });

        // AttempLogout();
      },
  
      async resetPassword(resetPasswordPayload) {
        return AttemptResetPassword(resetPasswordPayload);
      },
  
      async resetPasswordRequest(resetPasswordRequestPayload) {
        return AttemptResetPasswordRequest(resetPasswordRequestPayload);
      },
  
      async signUp(signUpPayload) {
        return AttemptSignUp(signUpPayload).then(({ authTokens, user }) => {
          this.user = user;
          this.authTokens = authTokens;
          this.expiresAt = authTokens.expiresIn
            ? Date.now() + authTokens.expiresIn * 1000
            : null;
  
          storage.setItem("___auth_user", JSON.stringify(user));
          storage.setItem("___auth_authTokens", JSON.stringify(authTokens));
          storage.setItem("___auth_expiresAt", JSON.stringify(this.expiresAt));
  
          return user;
        });
      },
  
      async updateAuthTokens() {
        if (this.refreshToken) {
          if (!this.refreshTokenPromise) {
            this.refreshTokenPromise = AttemptRefreshToken(
              this.refreshToken
            ).then((authTokens) => {
              this.authTokens = authTokens;
              this.expiresAt = authTokens.expiresIn
                ? Date.now() + authTokens.expiresIn * 1000
                : null;
  
              storage.setItem("___auth_authTokens", JSON.stringify(authTokens));
              storage.setItem(
                "___auth_expiresAt",
                JSON.stringify(this.expiresAt)
              );
  
              this.refreshTokenPromise = null;
            });
          }
  
          return this.refreshTokenPromise?.catch(() => {
            this.logout();
          });
        }
      },
  
      async updateUser(user) {
        this.user = user;
  
        storage.setItem("___auth_user", JSON.stringify(user));
      },
    },
  
    getters: {
      accessToken: (state) => {
        return state.authTokens?.accessToken || null;
      },
  
      expiresIn: (state) => {
        return state.authTokens?.expiresIn || null;
      },
  
      getUser: (state) => {
        return state.user;
      },
  
      isLoggedIn: (state) => {
        return !!state.user;
      },
  
      refreshToken: (state) => {
        return state.authTokens?.refreshToken || null;
      },
    },
  
    state: () => {
      return {
        authTokens: storage.getItem("___auth_authTokens")
          ? JSON.parse(storage.getItem("___auth_authTokens"))
          : null,
        expiresAt: storage.getItem("___auth_expiresAt")
          ? JSON.parse(storage.getItem("___auth_expiresAt"))
          : null,
        refreshTokenPromise: null,
        user: storage.getItem("___auth_user")
          ? JSON.parse(storage.getItem("___auth_user"))
          : null,
      };
    },
  });
  