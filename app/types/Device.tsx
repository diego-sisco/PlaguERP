import { QuestionType } from "./Question";

export type DeviceType = {
    id: number;
    floorplanID: number;
    name: string;
    number: number;
    area: string;
    version: number;
    is_scanned: boolean;
    updated_at: number;
    questions: QuestionType[];
};