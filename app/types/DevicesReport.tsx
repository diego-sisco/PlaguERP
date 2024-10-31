import { ReviewDevice } from "./ReviewDevice";

export type DevicesReport = {
  orderID: number;
  recs: string | null; // recomments
  tech_obs: string | null; // technical_observation
  comments: string | null; // comments
  signature: string | null;
  signatureName: string | null;
  completed_date: string | null;
  end_time: string | null;
  incidents: ReviewDevice[];
  is_sending: boolean; 
  user_id: string | number | null;
};
