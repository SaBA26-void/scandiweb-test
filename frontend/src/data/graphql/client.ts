const GRAPHQL_URL = "/graphql";

type GraphQLResponse<T> = {
  data?: T;
  errors?: Array<{ message?: string }>;
};

export async function runQuery<TData>(
  query: string,
  variables?: Record<string, unknown>
): Promise<TData> {
  const response = await fetch(GRAPHQL_URL, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ query, variables }),
  });

  if (!response.ok) {
    throw new Error("Backend request failed.");
  }

  const payload = (await response.json()) as GraphQLResponse<TData>;
  if (!payload.data) {
    const errorMessage = payload.errors?.[0]?.message ?? "GraphQL request failed.";
    throw new Error(errorMessage);
  }

  return payload.data;
}
