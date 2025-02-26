// Api Responsve Types
export type ApiResponse<TData> = {
  success: boolean;
  data: TData;
};

export type ApiError<TData> = ApiResponse<TData> & {
  statusCode: number;
};

export type ValidationErrors = {
  [key: string]: string[];
};

// Model types
export type User = {
  name: string;
  email: string;
};
