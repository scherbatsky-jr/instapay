import {
    loginMutation,
    logoutMutation,
    refreshTokenMutation,
    resetPasswordMutation,
    resetPasswordRequestMutation,
    signUpMutation,
  } from "@/graphql/auth";
  import client from "@/apolloClient";
  
  export const login = (
    credential
  ) => {
    return client
      .mutate({
        mutation: loginMutation,
        variables: credential,
      })
      .then((response) => {
        return {
          authTokens: response.data.login.authTokens,
          user: response.data.login.user,
        };
      })
      .catch(() => {
        throw [
          {
            code: "auth.invalid_credentials",
            message: "Invalid credentials",
          },
        ]
      });
  };
  
  export const logout = () => {
    return client
      .mutate({
        mutation: logoutMutation,
      })
      .then((response) => {
        return response.data;
      });
  };
  
  export const resetPasswordRequest = (
    resetPasswordRequestPayload
  ) => {
    return client
      .mutate({
        mutation: resetPasswordRequestMutation,
        variables: resetPasswordRequestPayload,
      })
      .then((response) => {
        return response.data.resetPasswordRequest.result;
      });
  };
  
  export const resetPassword = (
    resetPasswordPayload
  ) => {
    return client
      .mutate({
        mutation: resetPasswordMutation,
        variables: resetPasswordPayload,
      })
      .then((response) => {
        return response.data.resetPasswordRequest.result;
      });
  };
  
  export const signUp = (
    signUpPayload
  ) => {
    return client
      .mutate({
        mutation: signUpMutation,
        variables: signUpPayload,
      })
      .then((response) => {
        return {
          authTokens: response.data.signup.authTokens,
          user: response.data.signup.user,
        };
      })
      .catch(() => {
        throw [
          {
            code: "auth.field_error.email",
            message: "This email already exists. Please sign in instead.",
          },
        ]
      });
  };
  
  export const refreshToken = (refreshToken) => {
    return client
      .mutate({
        mutation: refreshTokenMutation,
        variables: {
          refreshToken: refreshToken,
        },
      })
      .then((response) => {
        return response.data.refreshToken
      });
  };
  