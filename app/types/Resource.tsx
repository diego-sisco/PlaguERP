export type InputResource = {
  product_id: number;
  appMethod_id: number;
  value: number | null;
};

export type Resource = {
  service_id: number;
  array_ids: InputResource[];
};